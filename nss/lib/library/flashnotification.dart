
import 'dart:async';
import 'dart:collection';
import 'dart:ui';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';


const double _kMinFlingVelocity = 700.0;
const double _kDismissThreshold = 0.5;

typedef FlashBuilder<T> = Widget Function(
    BuildContext context, FlashController<T> controller);

Future<T?> showFlash<T>({
  required BuildContext context,
  required FlashBuilder<T> builder,
  Duration? duration,
  Duration transitionDuration = const Duration(milliseconds: 500),
  bool persistent = true,
  WillPopCallback? onWillPop,
}) {
  return FlashController<T>(
    context,
    builder: builder,
    duration: duration,
    transitionDuration: transitionDuration,
    persistent: persistent,
    onWillPop: onWillPop,
  ).show();
}

class FlashController<T> {
  OverlayState? overlay;
  final ModalRoute? route;
  final BuildContext context;
  final FlashBuilder<T> builder;

  /// How long until Flash will hide itself (be dismissed). To make it indefinite, leave it null.
  final Duration? duration;

  /// Use it to speed up or slow down the animation duration
  final Duration? transitionDuration;

  /// Whether this Flash is add to route.
  ///
  /// Must be non-null, defaults to `true`
  ///
  /// If `true` the Flash will not add to route.
  ///
  /// If `false`, the Flash will add to route as a [LocalHistoryEntry]. Typically the page is wrap with Overlay.
  ///
  /// This can be useful in situations where the app needs to dismiss the Flash with [Navigator.pop].
  ///
  /// ```dart
  /// Navigator.of(context).push(MaterialPageRoute(builder: (context) {
  ///   return Overlay(
  ///     initialEntries: [
  ///       OverlayEntry(builder: (context) {
  ///         return FlashPage();
  ///       }),
  ///     ],
  ///   );
  /// }
  /// ```
  final bool persistent;

  /// Called to veto attempts by the user to dismiss the enclosing [ModalRoute].
  ///
  /// If the callback returns a Future that resolves to false, the enclosing
  /// route will not be popped.
  final WillPopCallback? onWillPop;

  /// The animation controller that the route uses to drive the transitions.
  ///
  /// The animation itself is exposed by the [animation] property.
  AnimationController get controller => _controller;
  late AnimationController _controller;
  Timer? _timer;
  LocalHistoryEntry? _historyEntry;
  bool _dismissed = false;
  bool _removedHistoryEntry = false;

  FlashController(
      this.context, {
        required this.builder,
        this.duration,
        this.transitionDuration = const Duration(milliseconds: 500),
        this.persistent = true,
        this.onWillPop,
      }) : route = ModalRoute.of(context) {
    final rootOverlay = Navigator.of(context, rootNavigator: true).overlay;
    if (persistent) {
      overlay = rootOverlay;
    } else {
      overlay = Overlay.of(context);
      assert(overlay != rootOverlay,
      '''overlay can't be the root overlay when persistent is false''');
    }
    assert(overlay != null);
    _controller = createAnimationController()
      ..addStatusListener(_handleStatusChanged);
  }

  bool get isDisposed => _transitionCompleter.isCompleted;

  /// A future that completes when this flash is popped.
  ///
  /// The future completes with the value given to [dismiss], if any.
  Future<T?> get popped => _transitionCompleter.future;
  final _transitionCompleter = Completer<T?>();

  late List<OverlayEntry> _overlayEntries;
  T? _result;

  Future<T?> show() {
    assert(!_transitionCompleter.isCompleted,
    'Cannot show a $runtimeType after disposing it.');

    if (onWillPop != null) route?.addScopedWillPopCallback(onWillPop!);
    overlay!.insertAll(_overlayEntries = _createOverlayEntries());
    _controller.forward();
    _configureTimer();
    _configurePersistent();

    return popped;
  }

  /// Called to create the animation controller that will drive the transitions to
  /// this route from the previous one, and back to the previous route from this
  /// one.
  AnimationController createAnimationController() {
    assert(!_transitionCompleter.isCompleted,
    'Cannot reuse a $runtimeType after disposing it.');
    final duration = transitionDuration;
    return AnimationController(
      duration: duration,
      debugLabel: debugLabel,
      vsync: overlay!,
    );
  }

  void _handleStatusChanged(AnimationStatus status) {
    switch (status) {
      case AnimationStatus.completed:
        if (_overlayEntries.isNotEmpty) _overlayEntries.first.opaque = false;
        break;
      case AnimationStatus.forward:
      case AnimationStatus.reverse:
        if (_overlayEntries.isNotEmpty) _overlayEntries.first.opaque = false;
        break;
      case AnimationStatus.dismissed:
      // We might still be an active route if a subclass is controlling the
      // the transition and hits the dismissed status. For example, the iOS
      // back gesture drives this animation to the dismissed status before
      // removing the route and disposing it.
        dispose();
        break;
    }
  }

  List<OverlayEntry> _createOverlayEntries() {
    List<OverlayEntry> overlays = [];

    overlays.add(
      OverlayEntry(
          builder: (BuildContext context) {
            return builder(context, this);
          },
          maintainState: false,
          opaque: false),
    );

    return overlays;
  }

  void _configurePersistent() {
    if (!persistent) {
      _historyEntry = LocalHistoryEntry(onRemove: () {
        assert(!_transitionCompleter.isCompleted,
        'Cannot reuse a $runtimeType after disposing it.');
        _removedHistoryEntry = true;
        if (!_dismissed) {
          if (onWillPop != null) route?.removeScopedWillPopCallback(onWillPop!);
          _cancelTimer();
          _controller.reverse();
        }
      });
      route?.addLocalHistoryEntry(_historyEntry!);
    }
  }

  void _removeLocalHistory() {
    if (!persistent && !_removedHistoryEntry) {
      _historyEntry!.remove();
      _removedHistoryEntry = true;
    }
  }

  void _configureTimer() {
    if (duration != null) {
      if (_timer?.isActive == true) {
        _timer!.cancel();
      }
      _timer = Timer(duration!, () {
        dismiss();
      });
    } else {
      _timer?.cancel();
    }
  }

  void _cancelTimer() {
    if (_timer?.isActive == true) {
      _timer!.cancel();
    }
  }

  @protected
  void dismissInternal() {
    _dismissed = true;
    if (onWillPop != null) route?.removeScopedWillPopCallback(onWillPop!);
    _removeLocalHistory();
    _cancelTimer();
  }

  Future<void> dismiss([T? result]) {
    assert(!_transitionCompleter.isCompleted,
    'Cannot reuse a $runtimeType after disposing it.');
    _dismissed = true;
    _result = result;
    if (onWillPop != null) route?.removeScopedWillPopCallback(onWillPop!);
    _removeLocalHistory();
    _cancelTimer();
    return _controller.reverse();
  }

  @protected
  void dispose() {
    assert(!_transitionCompleter.isCompleted,
    'Cannot dispose a $runtimeType twice.');
    dismissInternal();

    for (OverlayEntry entry in _overlayEntries) entry.remove();
    _overlayEntries.clear();
    _controller.dispose();
    _transitionCompleter.complete(_result);
  }

  /// A short description of this route useful for debugging.
  String get debugLabel => '$runtimeType';

  @override
  String toString() => '$runtimeType(animation: $_controller)';
}

/// A highly customizable widget so you can notify your user when you fell like he needs a beautiful explanation.
class Flash<T> extends StatefulWidget {
  Flash({
    Key? key,
    required this.controller,
    required this.child,
    this.constraints,
    this.margin = EdgeInsets.zero,
    this.borderRadius,
    this.borderColor,
    this.borderWidth,
    this.brightness = Brightness.light,
    this.backgroundColor = Colors.white,
    this.boxShadows,
    this.backgroundGradient,
    this.onTap,
    this.enableVerticalDrag = true,
    this.horizontalDismissDirection,
    this.insetAnimationDuration = const Duration(milliseconds: 100),
    this.insetAnimationCurve = Curves.fastOutSlowIn,
    this.alignment,
    this.position,
    this.behavior,
    this.forwardAnimationCurve = Curves.fastOutSlowIn,
    this.reverseAnimationCurve = Curves.fastOutSlowIn,
    this.barrierBlur,
    this.barrierColor,
    this.barrierDismissible = true,
    this.useSafeArea = true,
  })  : assert(() {
    if (alignment == null)
      return behavior != null && position != null;
    else
      return behavior == null && position == null;
  }()),
        super(key: key);

  Flash.bar({
    Key? key,
    required this.controller,
    required this.child,
    this.constraints,
    this.margin = EdgeInsets.zero,
    this.borderRadius,
    this.borderColor,
    this.borderWidth,
    this.brightness = Brightness.light,
    this.backgroundColor = Colors.white,
    this.boxShadows,
    this.backgroundGradient,
    this.onTap,
    this.enableVerticalDrag = true,
    this.horizontalDismissDirection,
    this.insetAnimationDuration = const Duration(milliseconds: 100),
    this.insetAnimationCurve = Curves.fastOutSlowIn,
    this.position = FlashPosition.bottom,
    this.behavior = FlashBehavior.floating,
    this.forwardAnimationCurve = Curves.fastOutSlowIn,
    this.reverseAnimationCurve = Curves.fastOutSlowIn,
    this.barrierBlur,
    this.barrierColor,
    this.barrierDismissible = true,
    this.useSafeArea = true,
  })  : alignment = null,
        assert(behavior != null),
        assert(position != null),
        super(key: key);

  Flash.dialog({
    Key? key,
    required this.controller,
    required this.child,
    this.constraints,
    this.margin = EdgeInsets.zero,
    this.borderRadius,
    this.borderColor,
    this.borderWidth,
    this.brightness = Brightness.light,
    this.backgroundColor = Colors.white,
    this.boxShadows,
    this.backgroundGradient,
    this.onTap,
    this.insetAnimationDuration = const Duration(milliseconds: 100),
    this.insetAnimationCurve = Curves.fastOutSlowIn,
    this.alignment = Alignment.center,
    this.forwardAnimationCurve = Curves.fastOutSlowIn,
    this.reverseAnimationCurve = Curves.fastOutSlowIn,
    this.barrierBlur,
    this.barrierColor = Colors.black54,
    this.barrierDismissible = true,
    this.useSafeArea = true,
  })  : enableVerticalDrag = false,
        horizontalDismissDirection = null,
        behavior = null,
        position = null,
        assert(alignment != null),
        assert(barrierColor != null),
        super(key: key);

  final FlashController controller;

  /// The widget below this widget in the tree.
  ///
  /// {@macro flutter.widgets.child}
  final Widget child;

  /// Additional constraints to apply to the child.
  final BoxConstraints? constraints;

  /// The brightness of the [backgroundColor] or [backgroundGradient]
  final Brightness brightness;

  /// Will be ignored if [backgroundGradient] is not null
  final Color? backgroundColor;

  /// [boxShadows] The shadows generated by Flashbar. Leave it null if you don't want a shadow.
  /// You can use more than one if you feel the need.
  /// Check (this example)[https://github.com/flutter/flutter/blob/master/packages/flutter/lib/src/material/shadows.dart]
  final List<BoxShadow>? boxShadows;

  /// Makes [backgroundColor] be ignored.
  final Gradient? backgroundGradient;

  /// A callback that registers the user's click anywhere. An alternative to [primaryAction]
  final GestureTapCallback? onTap;

  /// Determines if the user can swipe vertically to dismiss the bar.
  /// It is recommended that you set [duration] != null if this is false.
  /// If the user swipes to dismiss no value will be returned.
  final bool enableVerticalDrag;

  /// Determines if the user can swipe horizontally to dismiss the bar.
  /// It is recommended that you set [duration] != null if this is false.
  /// If the user swipes to dismiss no value will be returned.
  final HorizontalDismissDirection? horizontalDismissDirection;

  /// The duration of the animation to show when the system keyboard intrudes
  /// into the space that the dialog is placed in.
  ///
  /// Defaults to 100 milliseconds.
  final Duration insetAnimationDuration;

  /// The curve to use for the animation shown when the system keyboard intrudes
  /// into the space that the dialog is placed in.
  ///
  /// Defaults to [Curves.fastOutSlowIn].
  final Curve insetAnimationCurve;

  /// Adds a custom margin to Flash.
  final EdgeInsets margin;

  /// Adds a radius to all corners of Flash. Best combined with [margin].
  final BorderRadius? borderRadius;

  /// Adds a border to every side of Flash.
  final Color? borderColor;

  /// Changes the width of the border if [borderColor] is specified.
  final double? borderWidth;

  /// How to align the flash.
  final AlignmentGeometry? alignment;

  /// Flash can be based on [FlashPosition.top] or on [FlashPosition.bottom] of your screen.
  final FlashPosition? position;

  /// Flash can be floating or be grounded to the edge of the screen.
  /// If [behavior] is grounded, I do not recommend using [margin] or [borderRadius].
  final FlashBehavior? behavior;

  /// The [Curve] animation used when show() is called. [Curves.fastOutSlowIn] is default.
  final Curve forwardAnimationCurve;

  /// The [Curve] animation used when dismiss() is called. [Curves.fastOutSlowIn] is default.
  final Curve reverseAnimationCurve;

  /// Creates a blurred overlay that prevents the user from interacting with the screen.
  /// The greater the value, the greater the blur.
  final double? barrierBlur;

  /// Make sure you use a color with transparency here e.g. Colors.grey[600].withOpacity(0.2).
  final Color? barrierColor;

  /// Only takes effect if [barrierBlur] or [barrierColor] is not null.
  /// Whether you can dismiss this flashbar by tapping the modal barrier.
  ///
  /// For example, when a dialog is on the screen, the page below the dialog is
  /// usually darkened by the modal barrier.
  ///
  /// If [barrierDismissible] is true, then tapping this barrier will cause the
  /// current flashbar to be dismiss (see [FlashController.dismiss]) with null as the value.
  ///
  /// If [barrierDismissible] is false, then tapping the barrier has no effect.
  ///
  /// See also:
  ///
  ///  * [barrierBlur], which controls the blur of the scrim for this flashbar.
  ///  * [barrierColor], which controls the color of the scrim for this flashbar.
  final bool barrierDismissible;

  /// Is used to indicate if the flashbar should only display in 'safe' areas of
  /// the screen not used by the operating system (see [SafeArea] for more details).
  final bool useSafeArea;

  @override
  State createState() => _FlashState<T>();
}

class _FlashState<T> extends State<Flash<T>> {
  final GlobalKey _childKey = GlobalKey(debugLabel: 'flashbar child');

  /// The node this scope will use for its root [FocusScope] widget.
  final FocusScopeNode focusScopeNode =
  FocusScopeNode(debugLabel: '$_FlashState Focus Scope');

  double get _childWidth {
    final box = _childKey.currentContext?.findRenderObject() as RenderBox;
    return box.size.width;
  }

  double get _childHeight {
    final box = _childKey.currentContext?.findRenderObject() as RenderBox;
    return box.size.height;
  }

  bool get enableVerticalDrag => widget.enableVerticalDrag;

  HorizontalDismissDirection? get horizontalDismissDirection =>
      widget.horizontalDismissDirection;

  bool get enableHorizontalDrag => widget.horizontalDismissDirection != null;

  FlashController get controller => widget.controller;

  AnimationController get animationController => controller.controller;

  late Animation<Offset> _animation;

  late Animation<Offset> _moveAnimation;

  bool _isDragging = false;

  double _dragExtent = 0.0;

  bool _isHorizontalDragging = false;

  bool get hasBarrier =>
      widget.barrierBlur != null || widget.barrierColor != null;

  @override
  void initState() {
    super.initState();
    animationController.addStatusListener(_handleStatusChanged);
    _moveAnimation = _animation = _createAnimation();
    if (hasBarrier) {
      // Check if controller.route and controller.route.navigator are not null before accessing focusScopeNode
      if (controller.route != null && controller.route!.navigator != null) {
        FocusScope.of(context).setFirstFocus(focusScopeNode);
      }
    }
  }

  @override
  void didUpdateWidget(Flash<T> oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (hasBarrier) {
      // Check if controller.route and controller.route.navigator are not null before accessing focusScopeNode
      if (controller.route != null && controller.route!.navigator != null) {
        FocusScope.of(context).setFirstFocus(focusScopeNode);
      }
    }
  }

  @override
  void dispose() {
    focusScopeNode.dispose();
    super.dispose();
  }

  bool get _dismissUnderway =>
      animationController.status == AnimationStatus.reverse ||
          animationController.status == AnimationStatus.dismissed;

  bool get _shouldIgnoreFocusRequest {
    return animationController.status == AnimationStatus.reverse ||
        (controller.route?.navigator?.userGestureInProgress ?? false);
  }

  @override
  Widget build(BuildContext context) {
    Widget child = widget.child;

    if (widget.borderRadius != null) {
      child = ClipRRect(
        borderRadius: widget.borderRadius!,
        child: child,
      );
    }

    if (widget.behavior == FlashBehavior.fixed && widget.useSafeArea) {
      child = SafeArea(
        bottom: widget.position == FlashPosition.bottom,
        top: widget.position == FlashPosition.top,
        child: child,
      );
    }

    child = Ink(
      decoration: BoxDecoration(
        color: widget.backgroundColor,
        gradient: widget.backgroundGradient,
        borderRadius: widget.borderRadius,
        border: widget.borderColor != null
            ? Border.all(
            color: widget.borderColor!, width: widget.borderWidth ?? 1.0)
            : null,
      ),
      child: child,
    );

    if (widget.onTap != null) {
      child = GestureDetector(
        behavior: HitTestBehavior.opaque,
        onTap: widget.onTap,
        child: child,
      );
    }

    child = Material(
      type: MaterialType.transparency,
      child: child,
    );

    if (widget.constraints != null) {
      child = ConstrainedBox(
        constraints: widget.constraints!,
        child: child,
      );
    }

    // https://github.com/sososdk/flash/issues/23
    if (widget.position == FlashPosition.top) {
      child = AnnotatedRegion<SystemUiOverlayStyle>(
        value: widget.brightness == Brightness.dark
            ? SystemUiOverlayStyle.light
            : SystemUiOverlayStyle.dark,
        child: child,
      );
    }

    child = GestureDetector(
      behavior: HitTestBehavior.opaque,
      onHorizontalDragUpdate:
      enableHorizontalDrag ? _handleHorizontalDragUpdate : null,
      onHorizontalDragEnd:
      enableHorizontalDrag ? _handleHorizontalDragEnd : null,
      onVerticalDragUpdate:
      enableVerticalDrag ? _handleVerticalDragUpdate : null,
      onVerticalDragEnd: enableVerticalDrag ? _handleVerticalDragEnd : null,
      child: child,
      excludeFromSemantics: true,
    );

    child = DecoratedBox(
      decoration: BoxDecoration(
        borderRadius: widget.borderRadius,
        boxShadow: widget.boxShadows,
      ),
      child: child,
    );

    if (widget.behavior == FlashBehavior.floating && widget.useSafeArea) {
      child = SafeArea(
        bottom: widget.position == FlashPosition.bottom,
        top: widget.position == FlashPosition.top,
        child: child,
      );
    }

    child = AnimatedPadding(
      padding: MediaQuery.of(context).viewInsets + widget.margin,
      duration: widget.insetAnimationDuration,
      curve: widget.insetAnimationCurve,
      child: child,
    );

    if (widget.alignment == null) {
      child = SlideTransition(
        key: _childKey,
        position: _moveAnimation,
        child: child,
      );
    } else {
      child = SlideTransition(
        key: _childKey,
        position: _moveAnimation,
        child: FadeTransition(
          opacity:
          animationController.drive(Tween<double>(begin: 0.0, end: 1.0)),
          child: child,
        ),
      );
    }

    child = Semantics(
      focused: false,
      scopesRoute: true,
      explicitChildNodes: true,
      child: Stack(
        children: <Widget>[
          if (hasBarrier)
            AnimatedBuilder(
              animation: animationController,
              builder: (context, child) {
                final bool ignoreEvents = _shouldIgnoreFocusRequest;
                focusScopeNode.canRequestFocus = !ignoreEvents;

                final value = animationController.value;
                final blur = (widget.barrierBlur ?? 0.0) * value;
                final color = widget.barrierColor ?? Colors.transparent;
                Widget child = Container(
                  constraints: BoxConstraints.expand(),
                  color: color.withOpacity(color.opacity * value),
                );
                // https://github.com/flutter/flutter/issues/77258
                if (blur > 0.0) {
                  child = BackdropFilter(
                    filter: ImageFilter.blur(sigmaX: blur, sigmaY: blur),
                    child: child,
                  );
                }
                return GestureDetector(
                  behavior: HitTestBehavior.translucent,
                  onTap: widget.barrierDismissible
                      ? () => controller.dismiss()
                      : null,
                  child: child,
                );
              },
            ),
          if (widget.alignment == null)
            Align(
              alignment: widget.position == FlashPosition.bottom
                  ? Alignment.bottomCenter
                  : Alignment.topCenter,
              child: child,
            )
          else if (widget.useSafeArea)
            SafeArea(child: Align(alignment: widget.alignment!, child: child))
          else
            Align(alignment: widget.alignment!, child: child)
        ],
      ),
    );
    return FocusScope(node: focusScopeNode, child: child);
  }

  /// Called to create the animation that exposes the current progress of
  /// the transition controlled by the animation controller created by
  /// [FlashController.createAnimationController].
  Animation<Offset> _createAnimation() {
    Animatable<Offset> animatable;
    if (widget.position == FlashPosition.top) {
      animatable =
          Tween<Offset>(begin: const Offset(0.0, -1.0), end: Offset.zero);
    } else if (widget.position == FlashPosition.bottom) {
      animatable =
          Tween<Offset>(begin: const Offset(0.0, 1.0), end: Offset.zero);
    } else {
      animatable =
          Tween<Offset>(begin: const Offset(0.0, 0.05), end: Offset.zero);
    }
    return CurvedAnimation(
      parent: animationController.view,
      curve: widget.forwardAnimationCurve,
      reverseCurve: widget.reverseAnimationCurve,
    ).drive(animatable);
  }

  void _handleHorizontalDragUpdate(DragUpdateDetails details) {
    assert(widget.enableVerticalDrag);
    if (_dismissUnderway) return;
    _isDragging = true;
    _isHorizontalDragging = true;
    final double delta = details.primaryDelta!;
    final double oldDragExtent = _dragExtent;
    switch (horizontalDismissDirection) {
      case HorizontalDismissDirection.horizontal:
        _dragExtent += delta;
        break;
      case HorizontalDismissDirection.endToStart:
        switch (Directionality.of(context)) {
          case TextDirection.rtl:
            if (_dragExtent + delta > 0) _dragExtent += delta;
            break;
          case TextDirection.ltr:
            if (_dragExtent + delta < 0) _dragExtent += delta;
            break;
        }
        break;
      case HorizontalDismissDirection.startToEnd:
        switch (Directionality.of(context)) {
          case TextDirection.rtl:
            if (_dragExtent + delta < 0) _dragExtent += delta;
            break;
          case TextDirection.ltr:
            if (_dragExtent + delta > 0) _dragExtent += delta;
            break;
        }
        break;
      default:
        throw ArgumentError(
            'Direction $horizontalDismissDirection not supported');
    }
    if (oldDragExtent.sign != _dragExtent.sign) {
      setState(() => _updateMoveAnimation());
    }
    if (_dragExtent > 0) {
      animationController.value -= (_dragExtent - oldDragExtent) / _childWidth;
    } else {
      animationController.value += (_dragExtent - oldDragExtent) / _childWidth;
    }
  }

  void _handleHorizontalDragEnd(DragEndDetails details) {
    assert(enableHorizontalDrag);
    if (_dismissUnderway) return;
    _isDragging = false;
    _dragExtent = 0.0;
    _isHorizontalDragging = false;
    if (animationController.status == AnimationStatus.completed) {
      setState(() => _moveAnimation = _animation);
    }
    if (details.velocity.pixelsPerSecond.dx.abs() > _kMinFlingVelocity) {
      final double flingVelocity =
          details.velocity.pixelsPerSecond.dx / _childHeight;
      switch (_describeFlingGesture(details.velocity.pixelsPerSecond.dx)) {
        case _FlingGestureKind.none:
          animationController.forward();
          break;
        case _FlingGestureKind.forward:
          animationController.fling(velocity: -flingVelocity);
          break;
        case _FlingGestureKind.reverse:
          animationController.fling(velocity: flingVelocity);
          break;
      }
    } else if (animationController.value < _kDismissThreshold) {
      animationController.fling(velocity: -1.0);
      controller.dismissInternal();
    } else {
      animationController.forward();
    }
  }

  _FlingGestureKind _describeFlingGesture(double dragExtent) {
    _FlingGestureKind kind = _FlingGestureKind.none;
    switch (horizontalDismissDirection) {
      case HorizontalDismissDirection.horizontal:
        if (dragExtent > 0) {
          kind = _FlingGestureKind.forward;
        } else {
          kind = _FlingGestureKind.reverse;
        }
        break;
      case HorizontalDismissDirection.endToStart:
        switch (Directionality.of(context)) {
          case TextDirection.rtl:
            if (dragExtent > 0) kind = _FlingGestureKind.forward;
            break;
          case TextDirection.ltr:
            if (dragExtent < 0) kind = _FlingGestureKind.reverse;
            break;
        }
        break;
      case HorizontalDismissDirection.startToEnd:
        switch (Directionality.of(context)) {
          case TextDirection.rtl:
            if (dragExtent < 0) kind = _FlingGestureKind.reverse;
            break;
          case TextDirection.ltr:
            if (dragExtent > 0) kind = _FlingGestureKind.forward;
            break;
        }
        break;
      default:
        throw ArgumentError(
            'Direction $horizontalDismissDirection not supported');
    }
    return kind;
  }

  void _handleVerticalDragUpdate(DragUpdateDetails details) {
    assert(widget.enableVerticalDrag);
    if (_dismissUnderway) return;
    _isDragging = true;
    if (widget.position == FlashPosition.top) {
      animationController.value += details.primaryDelta! / _childHeight;
    } else {
      animationController.value -= details.primaryDelta! / _childHeight;
    }
  }

  void _handleVerticalDragEnd(DragEndDetails details) {
    assert(widget.enableVerticalDrag);
    if (_dismissUnderway) return;
    _isDragging = false;
    _dragExtent = 0.0;
    _isHorizontalDragging = false;
    if (animationController.status == AnimationStatus.completed) {
      setState(() => _moveAnimation = _animation);
    }
    if (details.velocity.pixelsPerSecond.dy.abs() > _kMinFlingVelocity) {
      final double flingVelocity =
          details.velocity.pixelsPerSecond.dy / _childHeight;
      if (widget.position == FlashPosition.top) {
        animationController.fling(velocity: flingVelocity);
      } else {
        animationController.fling(velocity: -flingVelocity);
      }
    } else if (animationController.value < _kDismissThreshold) {
      animationController.fling(velocity: -1.0);
      controller.dismissInternal();
    } else {
      animationController.forward();
    }
  }

  void _handleStatusChanged(AnimationStatus status) {
    switch (status) {
      case AnimationStatus.completed:
        if (!_isDragging) {
          setState(() => _moveAnimation = _animation);
        }
        break;
      case AnimationStatus.forward:
      case AnimationStatus.reverse:
        if (_isDragging) {
          setState(() => _updateMoveAnimation());
        }
        break;
      case AnimationStatus.dismissed:
        break;
    }
  }

  void _updateMoveAnimation() {
    Animatable<Offset> animatable;
    if (_isHorizontalDragging == true) {
      final double end = _dragExtent.sign;
      animatable = Tween<Offset>(begin: Offset(end, 0.0), end: Offset.zero);
    } else {
      if (widget.position == FlashPosition.top) {
        animatable =
            Tween<Offset>(begin: const Offset(0.0, -1.0), end: Offset.zero);
      } else {
        animatable =
            Tween<Offset>(begin: const Offset(0.0, 1.0), end: Offset.zero);
      }
    }
    _moveAnimation = animationController.drive(animatable);
  }
}

/// Indicates if flash is going to start at the [top] or at the [bottom].
enum FlashPosition { top, bottom }

/// Indicates if flash will be attached to the edge of the screen or not.
enum FlashBehavior { floating, fixed }

/// The direction in which a [HorizontalDismissDirection] can be dismissed.
enum HorizontalDismissDirection {
  /// The [HorizontalDismissDirection] can be dismissed by dragging either left or right.
  horizontal,

  /// The [HorizontalDismissDirection] can be dismissed by dragging in the reverse of the
  /// reading direction (e.g., from right to left in left-to-right languages).
  endToStart,

  /// The [HorizontalDismissDirection] can be dismissed by dragging in the reading direction
  /// (e.g., from left to right in left-to-right languages).
  startToEnd,
}

enum _FlingGestureKind { none, forward, reverse }

class FlashBar extends StatefulWidget {
  FlashBar({
    Key? key,
    this.padding = const EdgeInsets.all(16.0),
    this.title,
    required this.content,
    this.icon,
    this.shouldIconPulse = true,
    this.indicatorColor,
    this.primaryAction,
    this.actions,
    this.showProgressIndicator = false,
    this.progressIndicatorController,
    this.progressIndicatorBackgroundColor,
    this.progressIndicatorValueColor,
  }) : super(key: key);

  /// The (optional) title of the flashbar is displayed in a large font at the top
  /// of the flashbar.
  ///
  /// Typically a [Text] widget.
  final Widget? title;

  /// The message of the flashbar is displayed in the center of the flashbar in
  /// a lighter font.
  ///
  /// Typically a [Text] widget.
  final Widget content;

  /// If not null, shows a left vertical bar to better indicate the humor of the notification.
  /// It is not possible to use it with a [Form] and I do not recommend using it with [LinearProgressIndicator]
  final Color? indicatorColor;

  /// You can use any widget here, but I recommend [Icon] or [Image] as indication of what kind
  /// of message you are displaying. Other widgets may break the layout
  final Widget? icon;

  /// An option to animate the icon (if present). Defaults to true.
  final bool shouldIconPulse;

  /// A widget if you need an action from the user.
  final Widget? primaryAction;

  /// The (optional) set of actions that are displayed at the bottom of the flashbar.
  ///
  /// Typically this is a list of [TextButton] widgets.
  ///
  /// These widgets will be wrapped in a [ButtonBar], which introduces 8 pixels
  /// of padding on each side.
  final List<Widget>? actions;

  /// True if you want to show a [LinearProgressIndicator].
  final bool showProgressIndicator;

  /// An optional [AnimationController] when you want to control the progress of your [LinearProgressIndicator].
  final AnimationController? progressIndicatorController;

  /// A [LinearProgressIndicator] configuration parameter.
  final Color? progressIndicatorBackgroundColor;

  /// A [LinearProgressIndicator] configuration parameter.
  final Animation<Color>? progressIndicatorValueColor;

  /// Adds a custom padding to Flashbar
  ///
  /// The default follows material design guide line
  final EdgeInsets padding;

  @override
  _FlashBarState createState() => _FlashBarState();
}

class _FlashBarState extends State<FlashBar> with SingleTickerProviderStateMixin {
  AnimationController? _fadeController;
  Animation<double>? _fadeAnimation;

  final double _initialOpacity = 1.0;
  final double _finalOpacity = 0.4;

  final Duration _pulseAnimationDuration = Duration(seconds: 1);

  late bool _isTitlePresent;
  late bool _isActionsPresent;
  late double _messageTopMargin;
  late double _messageBottomMargin;

  @override
  void initState() {
    super.initState();

    _isTitlePresent = widget.title != null;
    _messageTopMargin = _isTitlePresent ? 6.0 : widget.padding.top;
    _isActionsPresent = widget.actions?.isNotEmpty == true;
    _messageBottomMargin = _isActionsPresent ? 6.0 : widget.padding.bottom;

    if (widget.icon != null && widget.shouldIconPulse) {
      _configurePulseAnimation();
      _fadeController!.forward();
    }
  }

  void _configurePulseAnimation() {
    _fadeController =
        AnimationController(vsync: this, duration: _pulseAnimationDuration);
    _fadeAnimation = Tween(begin: _initialOpacity, end: _finalOpacity).animate(
      CurvedAnimation(
        parent: _fadeController!,
        curve: Curves.linear,
      ),
    );

    _fadeController!.addStatusListener((status) {
      if (status == AnimationStatus.completed) {
        _fadeController!.reverse();
      }
      if (status == AnimationStatus.dismissed) {
        _fadeController!.forward();
      }
    });

    _fadeController!.forward();
  }

  @override
  void dispose() {
    _fadeController?.dispose();

    widget.progressIndicatorController?.dispose();

    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Column(
      mainAxisSize: MainAxisSize.min,
      children: [
        if (widget.showProgressIndicator)
          if (widget.progressIndicatorController == null)
            LinearProgressIndicator(
              backgroundColor: widget.progressIndicatorBackgroundColor,
              valueColor: widget.progressIndicatorValueColor,
            )
          else
            AnimatedBuilder(
              animation: widget.progressIndicatorController!,
              builder: (context, child) {
                return LinearProgressIndicator(
                  value: widget.progressIndicatorController!.value,
                  backgroundColor: widget.progressIndicatorBackgroundColor,
                  valueColor: widget.progressIndicatorValueColor,
                );
              },
            ),
        IntrinsicHeight(
          child: Row(
            mainAxisSize: MainAxisSize.min,
            children: _getAppropriateRowLayout(),
          ),
        ),
      ],
    );
  }

  List<Widget> _getAppropriateRowLayout() {
    double buttonRightPadding;
    double iconPadding = 0;
    if (widget.padding.right - 12 < 0) {
      buttonRightPadding = 4;
    } else {
      buttonRightPadding = widget.padding.right - 12;
    }

    if (widget.padding.left > 16.0) {
      iconPadding = widget.padding.left;
    }

    if (widget.icon == null && widget.primaryAction == null) {
      return [
        if (widget.indicatorColor != null)
          Container(
            color: widget.indicatorColor,
            width: 5.0,
          ),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: <Widget>[
              if (_isTitlePresent)
                Padding(
                  padding: EdgeInsets.only(
                    top: widget.padding.top,
                    left: widget.padding.left,
                    right: widget.padding.right,
                  ),
                  child: _getTitle(),
                ),
              Padding(
                padding: EdgeInsets.only(
                  top: _messageTopMargin,
                  left: widget.padding.left,
                  right: widget.padding.right,
                  bottom: _messageBottomMargin,
                ),
                child: _getMessage(),
              ),
              if (_isActionsPresent)
                ButtonTheme(
                  padding: EdgeInsets.symmetric(horizontal: buttonRightPadding),
                  child: ButtonBar(
                    children: widget.actions!,
                  ),
                ),
            ],
          ),
        ),
      ];
    } else if (widget.icon != null && widget.primaryAction == null) {
      return <Widget>[
        if (widget.indicatorColor != null)
          Container(
            color: widget.indicatorColor,
            width: 5.0,
          ),
        Expanded(
          child: Column(
            children: <Widget>[
              Row(
                children: <Widget>[
                  ConstrainedBox(
                    constraints: BoxConstraints(minWidth: 42.0 + iconPadding),
                    child: _getIcon(),
                  ),
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: <Widget>[
                        if (_isTitlePresent)
                          Padding(
                            padding: EdgeInsets.only(
                              top: widget.padding.top,
                              left: 4.0,
                              right: widget.padding.left,
                            ),
                            child: _getTitle(),
                          ),
                        Padding(
                          padding: EdgeInsets.only(
                            top: _messageTopMargin,
                            left: 4.0,
                            right: widget.padding.right,
                            bottom: _messageBottomMargin,
                          ),
                          child: _getMessage(),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              if (_isActionsPresent)
                ButtonTheme(
                  padding: EdgeInsets.symmetric(horizontal: buttonRightPadding),
                  child: ButtonBar(
                    children: widget.actions!,
                  ),
                ),
            ],
          ),
        ),
      ];
    } else if (widget.icon == null && widget.primaryAction != null) {
      return <Widget>[
        if (widget.indicatorColor != null)
          Container(
            color: widget.indicatorColor,
            width: 5.0,
          ),
        Expanded(
          child: Column(
            children: <Widget>[
              Row(
                children: <Widget>[
                  Expanded(
                    child: Column(
                      crossAxisAlignment: CrossAxisAlignment.stretch,
                      children: <Widget>[
                        if (_isTitlePresent)
                          Padding(
                            padding: EdgeInsets.only(
                              top: widget.padding.top,
                              left: widget.padding.left,
                              right: widget.padding.right,
                            ),
                            child: _getTitle(),
                          ),
                        Padding(
                          padding: EdgeInsets.only(
                            top: _messageTopMargin,
                            left: widget.padding.left,
                            right: 4.0,
                            bottom: _messageBottomMargin,
                          ),
                          child: _getMessage(),
                        ),
                      ],
                    ),
                  ),
                  Padding(
                    padding: EdgeInsets.only(right: buttonRightPadding),
                    child: _getPrimaryAction(),
                  ),
                ],
              ),
              if (_isActionsPresent)
                ButtonTheme(
                  padding: EdgeInsets.symmetric(horizontal: buttonRightPadding),
                  child: ButtonBar(
                    children: widget.actions!,
                  ),
                ),
            ],
          ),
        ),
      ];
    } else {
      return <Widget>[
        if (widget.indicatorColor != null)
          Container(
            color: widget.indicatorColor,
            width: 5.0,
          ),
        Expanded(
          child: Column(
            children: <Widget>[
              Expanded(
                child: Row(
                  children: <Widget>[
                    ConstrainedBox(
                      constraints: BoxConstraints(minWidth: 42.0 + iconPadding),
                      child: _getIcon(),
                    ),
                    Expanded(
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: <Widget>[
                          if (_isTitlePresent)
                            Padding(
                              padding: EdgeInsets.only(
                                top: widget.padding.top,
                                left: 4.0,
                                right: 4.0,
                              ),
                              child: _getTitle(),
                            ),
                          Padding(
                            padding: EdgeInsets.only(
                              top: _messageTopMargin,
                              left: 4.0,
                              right: 4.0,
                              bottom: _messageBottomMargin,
                            ),
                            child: _getMessage(),
                          ),
                        ],
                      ),
                    ),
                    Padding(
                      padding: EdgeInsets.only(right: buttonRightPadding),
                      child: _getPrimaryAction(),
                    ),
                  ],
                ),
              ),
              if (_isActionsPresent)
                ButtonTheme(
                  padding: EdgeInsets.symmetric(horizontal: buttonRightPadding),
                  child: ButtonBar(
                    children: widget.actions!,
                  ),
                ),
            ],
          ),
        ),
      ];
    }
  }

  Widget _getIcon() {
    assert(widget.icon != null);
    Widget child;
    if (widget.shouldIconPulse) {
      child = FadeTransition(
        opacity: _fadeAnimation!,
        child: widget.icon,
      );
    } else {
      child = widget.icon!;
    }
    return child;
  }

  Widget _getTitle() {
    return Semantics(
      child: widget.title,
      namesRoute: true,
      container: true,
    );
  }

  Widget _getMessage() {
    return widget.content;
  }

  Widget _getPrimaryAction() {
    assert(widget.primaryAction != null);
    final buttonTheme = ButtonTheme.of(context);
    return ButtonTheme(
      textTheme: ButtonTextTheme.primary,
      child: IconTheme(
        data: Theme.of(context)
            .iconTheme
            .copyWith(color: buttonTheme.colorScheme?.primary),
        child: widget.primaryAction!,
      ),
    );
  }
}


/// Wrap a widget with [Overlay].
Widget wrapWithOverlay({
  required WidgetBuilder builder,
  Clip clipBehavior = Clip.hardEdge,
}) {
  return Overlay(
    initialEntries: [OverlayEntry(builder: builder)],
    clipBehavior: clipBehavior,
  );
}

/// A widget provide the function of emit message.
class Toast extends StatefulWidget {
  /// The widget below this widget in the tree.
  final Widget child;

  /// A key to use when building the [Navigator].
  final GlobalKey<NavigatorState> navigatorKey;

  /// How to align the toast.
  final Alignment alignment;

  /// Adds a custom margin to toast.
  final EdgeInsets margin;

  /// Adds a radius to all corners of toast..
  final BorderRadius borderRadius;

  /// Style for the text message.
  final TextStyle textStyle;

  /// The amount of space to surround the message with.
  final EdgeInsets padding;

  /// Creates a toast to emit message.
  const Toast({
    Key? key,
    required this.child,
    required this.navigatorKey,
    this.alignment = const Alignment(0, 0.5),
    this.margin = const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
    this.borderRadius = const BorderRadius.all(Radius.circular(8.0)),
    this.textStyle = const TextStyle(fontSize: 16.0, color: Colors.white),
    this.padding = const EdgeInsets.symmetric(horizontal: 16.0, vertical: 8.0),
  }) : super(key: key);

  /// Emit a message for the specified duration.
  static Future<T?> show<T>(
      BuildContext context,
      Object message, {
        Duration duration = const Duration(seconds: 3),
        Alignment? alignment,
        EdgeInsets? margin,
        BorderRadius? borderRadius,
        Color? backgroundColor,
        TextStyle? textStyle,
        EdgeInsets? padding,
      }) {
    return context.findAncestorStateOfType<_ToastState>()!.show(message,
        duration: duration,
        alignment: alignment,
        margin: margin,
        borderRadius: borderRadius,
        backgroundColor: backgroundColor,
        textStyle: textStyle,
        padding: padding);
  }

  @override
  _ToastState createState() => _ToastState();
}

class _ToastState extends State<Toast> {
  final messageQueue = Queue<_MessageItem>();
  final initialized = Completer();
  Completer? messageCompleter;

  @override
  void dispose() {
    messageQueue.clear();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (!initialized.isCompleted) initialized.complete();
    return widget.child;
  }

  Future<T?> show<T>(
      Object message, {
        Duration duration = const Duration(seconds: 3),
        Alignment? alignment,
        EdgeInsets? margin,
        BorderRadius? borderRadius,
        Color? backgroundColor,
        TextStyle? textStyle,
        EdgeInsets? padding,
      }) async {
    // Wait initialized.
    await initialized.future;

    final context = widget.navigatorKey.currentContext!;

    // Wait previous toast dismissed.
    if (messageCompleter?.isCompleted == false) {
      final item = _MessageItem<T>(message, duration, alignment, margin,
          borderRadius, backgroundColor, textStyle, padding);
      messageQueue.add(item);
      return await item.completer.future;
    }

    messageCompleter = Completer();

    Future<T?> showToast(Object message, Duration duration, alignment, margin,
        borderRadius, backgroundColor, textStyle, padding) {
      return showFlash<T>(
        context: context,
        builder: (context, controller) {
          final child;
          if (message is WidgetBuilder) {
            child = message(context);
          } else if (message is Widget) {
            child = message;
          } else {
            child = Text(message.toString());
          }
          return Flash<T>(
            controller: controller,
            alignment: alignment ?? widget.alignment,
            margin: margin ?? widget.margin,
            borderRadius: borderRadius ?? widget.borderRadius,
            backgroundColor: backgroundColor ?? Colors.black87,
            child: Padding(
              padding: padding ?? widget.padding,
              child: DefaultTextStyle(
                style: textStyle ?? widget.textStyle,
                child: child,
              ),
            ),
          );
        },
        duration: duration,
      ).whenComplete(() {
        if (messageQueue.isNotEmpty) {
          final item = messageQueue.removeFirst();
          item.completer.complete(showToast(
              item.message,
              item.duration,
              item.alignment,
              item.margin,
              item.borderRadius,
              item.backgroundColor,
              item.textStyle,
              item.padding));
        } else {
          messageCompleter?.complete();
        }
      });
    }

    return showToast(message, duration, alignment, margin, borderRadius,
        backgroundColor, textStyle, padding);
  }
}

class _MessageItem<T> {
  final Object message;
  final Duration duration;
  final Alignment? alignment;
  final EdgeInsets? margin;
  final BorderRadius? borderRadius;
  final Color? backgroundColor;
  final TextStyle? textStyle;
  final EdgeInsets? padding;
  final Completer<T?> completer;

  _MessageItem(this.message, this.duration, this.alignment, this.margin,
      this.borderRadius, this.backgroundColor, this.textStyle, this.padding)
      : completer = Completer<T?>();
}

/// Context extension for flash toast.
extension ToastShortcuts on BuildContext {
  /// Emit a message for the specified duration.
  Future<T?> showToast<T>(
      Object message, {
        Duration duration = const Duration(seconds: 3),
        Alignment? alignment,
        EdgeInsets? margin,
        BorderRadius? borderRadius,
        Color? backgroundColor,
        TextStyle? textStyle,
        EdgeInsets? padding,
      }) {
    assert(message is String || message is Widget || message is WidgetBuilder);
    return Toast.show(this, message,
        duration: duration,
        alignment: alignment,
        margin: margin,
        borderRadius: borderRadius,
        backgroundColor: backgroundColor,
        textStyle: textStyle,
        padding: padding);
  }
}

/// Context extension for flash bar.
extension FlashBarShortcuts on BuildContext {
  /// Show a custom flash bar.
  Future<T?> showFlashBar<T>({
    FlashBarType? barType,
    bool persistent = true,
    Duration? duration,
    Duration? transitionDuration,
    WillPopCallback? onWillPop,
    bool? enableVerticalDrag,
    HorizontalDismissDirection? horizontalDismissDirection,
    FlashBehavior? behavior,
    FlashPosition? position,
    Brightness? brightness,
    Color? backgroundColor,
    TextStyle? titleStyle,
    TextStyle? contentStyle,
    Color? actionColor,
    Gradient? backgroundGradient,
    List<BoxShadow>? boxShadows,
    double? barrierBlur,
    Color? barrierColor,
    bool? barrierDismissible,
    BorderRadius? borderRadius,
    Color? borderColor,
    double? borderWidth,
    EdgeInsets? margin,
    Duration? insetAnimationDuration,
    Curve? insetAnimationCurve,
    Curve? forwardAnimationCurve,
    Curve? reverseAnimationCurve,
    FlashCallback? onTap,
    EdgeInsets? padding,
    Widget? title,
    required Widget content,
    BoxConstraints? constraints,
    bool shouldIconPulse = true,
    Widget? icon,
    Color? indicatorColor,
    FlashWidgetBuilder<T>? primaryActionBuilder,
    FlashWidgetBuilder<T>? negativeActionBuilder,
    FlashWidgetBuilder<T>? positiveActionBuilder,
    bool showProgressIndicator = false,
    AnimationController? progressIndicatorController,
    Color? progressIndicatorBackgroundColor,
    Animation<Color>? progressIndicatorValueColor,
    Completer<T>? dismissCompleter,
  }) {
    final flashTheme = FlashTheme.bar(this);
    final controller = FlashController<T>(
      this,
      persistent: persistent,
      duration: duration,
      transitionDuration: transitionDuration ?? flashTheme.transitionDuration,
      onWillPop: onWillPop,
      builder: (context, controller) {
        return StatefulBuilder(builder: (context, setState) {
          final theme = Theme.of(context);
          final colorScheme = theme.colorScheme;
          final isThemeDark = theme.brightness == Brightness.dark;
          final $brightness = brightness ??
              flashTheme.brightness ??
              (isThemeDark ? Brightness.light : Brightness.dark);
          final $backgroundColor = backgroundColor ??
              flashTheme.backgroundColor ??
              (isThemeDark
                  ? theme.colorScheme.onSurface
                  : Color.alphaBlend(
                theme.colorScheme.onSurface.withOpacity(0.80),
                theme.colorScheme.surface,
              ));
          final $titleColor = titleStyle?.color ??
              flashTheme.titleStyle?.color ??
              theme.colorScheme.surface;
          final $contentColor = contentStyle?.color ??
              flashTheme.contentStyle?.color ??
              theme.colorScheme.surface;
          final $actionColor = actionColor ??
              flashTheme.actionColor ??
              (isThemeDark
                  ? theme.colorScheme.primaryContainer
                  : theme.colorScheme.secondary);

          final inverseTheme = theme.copyWith(
            colorScheme: ColorScheme(
              primary: colorScheme.onPrimary,
              primaryContainer: colorScheme.onPrimary,
              secondary: $actionColor,
              secondaryContainer: colorScheme.onSecondary,
              surface: colorScheme.onSurface,
              background: $backgroundColor,
              error: colorScheme.onError,
              onPrimary: colorScheme.primary,
              onSecondary: colorScheme.secondary,
              onSurface: colorScheme.surface,
              onBackground: colorScheme.background,
              onError: colorScheme.error,
              brightness: $brightness,
            ),
            textTheme: theme.textTheme.apply(
              displayColor: $titleColor,
              bodyColor: $contentColor,
            ),
          );

          Color? $indicatorColor;
          if (barType == FlashBarType.info) {
            $indicatorColor = flashTheme.infoColor;
          } else if (barType == FlashBarType.success) {
            $indicatorColor = flashTheme.successColor;
          } else if (barType == FlashBarType.error) {
            $indicatorColor = flashTheme.errorColor;
          } else {
            $indicatorColor = indicatorColor;
          }

          Widget wrapActionBuilder(FlashWidgetBuilder<T> builder) {
            Widget child = IconTheme(
              data: IconThemeData(color: $actionColor),
              child: builder.call(context, controller, setState),
            );
            child = TextButtonTheme(
              data: TextButtonThemeData(
                style: TextButton.styleFrom(foregroundColor: $actionColor),
              ),
              child: child,
            );
            return child;
          }

          return Theme(
            data: inverseTheme,
            child: Flash<T>.bar(
              controller: controller,
              behavior: behavior ?? flashTheme.behavior ?? FlashBehavior.fixed,
              position: position ?? flashTheme.position ?? FlashPosition.bottom,
              enableVerticalDrag:
              enableVerticalDrag ?? flashTheme.enableVerticalDrag ?? true,
              horizontalDismissDirection: horizontalDismissDirection ??
                  flashTheme.horizontalDismissDirection,
              brightness: $brightness,
              backgroundColor: $backgroundColor,
              backgroundGradient:
              backgroundGradient ?? flashTheme.backgroundGradient,
              boxShadows: boxShadows ?? flashTheme.boxShadows,
              barrierBlur: barrierBlur ?? flashTheme.barrierBlur,
              barrierColor: barrierColor ?? flashTheme.barrierColor,
              barrierDismissible:
              barrierDismissible ?? flashTheme.barrierDismissible ?? true,
              borderRadius: borderRadius ?? flashTheme.borderRadius,
              borderColor: borderColor ?? flashTheme.borderColor,
              borderWidth: borderWidth ?? flashTheme.borderWidth,
              margin: margin ?? flashTheme.margin ?? EdgeInsets.zero,
              insetAnimationDuration: insetAnimationDuration ??
                  flashTheme.insetAnimationDuration ??
                  const Duration(milliseconds: 100),
              insetAnimationCurve: insetAnimationCurve ??
                  flashTheme.insetAnimationCurve ??
                  Curves.fastOutSlowIn,
              forwardAnimationCurve: forwardAnimationCurve ??
                  flashTheme.forwardAnimationCurve ??
                  Curves.fastOutSlowIn,
              reverseAnimationCurve: reverseAnimationCurve ??
                  flashTheme.reverseAnimationCurve ??
                  Curves.fastOutSlowIn,
              onTap: onTap == null
                  ? null
                  : () => onTap(context, controller, setState),
              constraints: constraints ?? flashTheme.constraints,
              child: FlashBar(
                padding:
                padding ?? flashTheme.padding ?? const EdgeInsets.all(16.0),
                title: title == null
                    ? null
                    : DefaultTextStyle(
                  style: inverseTheme.textTheme.titleLarge!
                      .merge(flashTheme.titleStyle)
                      .merge(titleStyle),
                  child: title,
                ),
                content: DefaultTextStyle(
                  style: inverseTheme.textTheme.titleMedium!
                      .merge(flashTheme.contentStyle)
                      .merge(contentStyle),
                  child: content,
                ),
                shouldIconPulse: shouldIconPulse,
                icon: icon == null
                    ? null
                    : IconTheme(
                  data: IconThemeData(color: $indicatorColor),
                  child: icon,
                ),
                indicatorColor: $indicatorColor,
                primaryAction: primaryActionBuilder == null
                    ? null
                    : wrapActionBuilder(primaryActionBuilder),
                actions: <Widget>[
                  if (negativeActionBuilder != null)
                    wrapActionBuilder(negativeActionBuilder),
                  if (positiveActionBuilder != null)
                    wrapActionBuilder(positiveActionBuilder),
                ],
                showProgressIndicator: showProgressIndicator,
                progressIndicatorController: progressIndicatorController,
                progressIndicatorBackgroundColor:
                progressIndicatorBackgroundColor,
                progressIndicatorValueColor: progressIndicatorValueColor,
              ),
            ),
          );
        });
      },
    );
    dismissCompleter?.future.then(controller.dismiss);
    return controller.show();
  }

  /// Show an information flash bar.
  Future<T?> showInfoBar<T>({
    required Widget content,
    FlashPosition? position,
    Duration duration = const Duration(seconds: 3),
    Icon? icon = const Icon(Icons.info_outline),
    FlashWidgetBuilder<T>? primaryActionBuilder,
  }) {
    return showFlashBar<T>(
      barType: FlashBarType.info,
      content: content,
      position: position,
      icon: icon,
      duration: duration,
      primaryActionBuilder: primaryActionBuilder,
    );
  }

  /// Show a success flash bar.
  Future<T?> showSuccessBar<T>({
    required Widget content,
    FlashPosition? position,
    Duration duration = const Duration(seconds: 3),
    Icon? icon = const Icon(Icons.check_circle_outline),
    FlashWidgetBuilder<T>? primaryActionBuilder,
  }) {
    return showFlashBar<T>(
      barType: FlashBarType.success,
      content: content,
      position: position,
      icon: icon,
      duration: duration,
      primaryActionBuilder: primaryActionBuilder,
    );
  }

  /// Show a error flash bar.
  Future<T?> showErrorBar<T>({
    required Widget content,
    FlashPosition? position,
    Duration duration = const Duration(seconds: 3),
    Icon? icon = const Icon(Icons.error_outline),
    FlashWidgetBuilder<T>? primaryActionBuilder,
  }) {
    return showFlashBar<T>(
      barType: FlashBarType.error,
      content: content,
      position: position,
      icon: icon,
      duration: duration,
      primaryActionBuilder: primaryActionBuilder,
    );
  }
}

/// Context extension for flash dialog.
extension FlashDialogShortcuts on BuildContext {
  /// Show a custom flash dialog.
  Future<T?> showFlashDialog<T>({
    bool persistent = true,
    Duration? transitionDuration,
    WillPopCallback? onWillPop,
    Brightness? brightness,
    Color? backgroundColor,
    TextStyle? titleStyle,
    TextStyle? contentStyle,
    Color? actionColor,
    Gradient? backgroundGradient,
    List<BoxShadow>? boxShadows,
    double? barrierBlur,
    Color? barrierColor,
    bool? barrierDismissible,
    BorderRadius? borderRadius,
    Color? borderColor,
    double? borderWidth,
    EdgeInsets? margin,
    Duration? insetAnimationDuration,
    Curve? insetAnimationCurve,
    Curve? forwardAnimationCurve,
    Curve? reverseAnimationCurve,
    FlashCallback? onTap,
    EdgeInsets? padding,
    Widget? title,
    required Widget content,
    BoxConstraints? constraints,
    FlashWidgetBuilder<T>? negativeActionBuilder,
    FlashWidgetBuilder<T>? positiveActionBuilder,
    Completer<T>? dismissCompleter,
  }) {
    final flashTheme = FlashTheme.dialog(this);
    final controller = FlashController<T>(
      this,
      persistent: persistent,
      transitionDuration: transitionDuration ?? flashTheme.transitionDuration,
      onWillPop: onWillPop,
      builder: (context, controller) {
        return StatefulBuilder(builder: (context, setState) {
          final theme = Theme.of(context);
          final dialogTheme = DialogTheme.of(context);
          final $brightness =
              brightness ?? flashTheme.brightness ?? theme.brightness;
          final $backgroundColor = backgroundColor ??
              flashTheme.backgroundColor ??
              dialogTheme.backgroundColor ??
              theme.dialogBackgroundColor;
          final $titleStyle = titleStyle ??
              flashTheme.titleStyle ??
              dialogTheme.titleTextStyle ??
              theme.textTheme.titleLarge!;
          final $contentStyle = contentStyle ??
              flashTheme.contentStyle ??
              dialogTheme.contentTextStyle ??
              theme.textTheme.titleMedium!;
          final $actionColor = actionColor ??
              flashTheme.actionColor ??
              theme.colorScheme.primary;
          Widget wrapActionBuilder(FlashWidgetBuilder<T> builder) {
            Widget child = IconTheme(
              data: IconThemeData(color: $actionColor),
              child: builder.call(context, controller, setState),
            );
            child = TextButtonTheme(
              data: TextButtonThemeData(
                style: TextButton.styleFrom(foregroundColor: $actionColor),
              ),
              child: child,
            );
            return child;
          }

          return Flash<T>.dialog(
            controller: controller,
            brightness: $brightness,
            backgroundColor: $backgroundColor,
            backgroundGradient:
            backgroundGradient ?? flashTheme.backgroundGradient,
            boxShadows: boxShadows ?? flashTheme.boxShadows,
            barrierBlur: barrierBlur ?? flashTheme.barrierBlur,
            barrierColor:
            barrierColor ?? flashTheme.barrierColor ?? Colors.black54,
            barrierDismissible:
            barrierDismissible ?? flashTheme.barrierDismissible ?? true,
            borderRadius: borderRadius ?? flashTheme.borderRadius,
            borderColor: borderColor ?? flashTheme.borderColor,
            borderWidth: borderWidth ?? flashTheme.borderWidth,
            margin: margin ??
                flashTheme.margin ??
                const EdgeInsets.symmetric(horizontal: 40.0),
            insetAnimationDuration: insetAnimationDuration ??
                flashTheme.insetAnimationDuration ??
                const Duration(milliseconds: 100),
            insetAnimationCurve: insetAnimationCurve ??
                flashTheme.insetAnimationCurve ??
                Curves.fastOutSlowIn,
            forwardAnimationCurve: forwardAnimationCurve ??
                flashTheme.forwardAnimationCurve ??
                Curves.fastOutSlowIn,
            reverseAnimationCurve: reverseAnimationCurve ??
                flashTheme.reverseAnimationCurve ??
                Curves.fastOutSlowIn,
            onTap: onTap == null
                ? null
                : () => onTap(context, controller, setState),
            constraints: constraints ?? flashTheme.constraints,
            child: FlashBar(
              padding:
              padding ?? flashTheme.padding ?? const EdgeInsets.all(16.0),
              title: title == null
                  ? null
                  : DefaultTextStyle(style: $titleStyle, child: title),
              content: DefaultTextStyle(style: $contentStyle, child: content),
              actions: <Widget>[
                if (negativeActionBuilder != null)
                  wrapActionBuilder(negativeActionBuilder),
                if (positiveActionBuilder != null)
                  wrapActionBuilder(positiveActionBuilder),
              ],
            ),
          );
        });
      },
    );
    dismissCompleter?.future.then(controller.dismiss);
    return controller.show();
  }

  /// Display a block dialog.
  Future<T?> showBlockDialog<T>({
    bool persistent = true,
    Duration? transitionDuration,
    Color? backgroundColor,
    Gradient? backgroundGradient,
    List<BoxShadow>? boxShadows,
    double? barrierBlur,
    Color? barrierColor,
    BorderRadius? borderRadius,
    Color? borderColor,
    double? borderWidth,
    EdgeInsets? margin,
    Duration? insetAnimationDuration,
    Curve? insetAnimationCurve,
    Curve? forwardAnimationCurve,
    Curve? reverseAnimationCurve,
    FlashCallback? onTap,
    EdgeInsets padding = const EdgeInsets.all(16.0),
    Widget child = const Padding(
      padding: EdgeInsets.all(16.0),
      child: CircularProgressIndicator(strokeWidth: 2.0),
    ),
    required Completer<T>? dismissCompleter,
  }) {
    final flashTheme = FlashTheme.blockDialog(this);
    final controller = FlashController<T>(
      this,
      persistent: persistent,
      transitionDuration: transitionDuration ?? flashTheme.transitionDuration,
      onWillPop: () => Future.value(false),
      builder: (context, controller) {
        return StatefulBuilder(builder: (context, setState) {
          final theme = Theme.of(context);
          final dialogTheme = DialogTheme.of(context);
          final $backgroundColor = backgroundColor ??
              flashTheme.backgroundColor ??
              dialogTheme.backgroundColor ??
              theme.dialogBackgroundColor;
          return Flash<T>.dialog(
            controller: controller,
            backgroundColor: $backgroundColor,
            backgroundGradient:
            backgroundGradient ?? flashTheme.backgroundGradient,
            boxShadows: boxShadows ?? flashTheme.boxShadows,
            barrierBlur: barrierBlur ?? flashTheme.barrierBlur,
            barrierColor:
            barrierColor ?? flashTheme.barrierColor ?? Colors.black54,
            barrierDismissible: false,
            borderRadius: borderRadius ?? flashTheme.borderRadius,
            borderColor: borderColor ?? flashTheme.borderColor,
            borderWidth: borderWidth ?? flashTheme.borderWidth,
            margin: margin ??
                flashTheme.margin ??
                const EdgeInsets.symmetric(horizontal: 40.0),
            insetAnimationDuration: insetAnimationDuration ??
                flashTheme.insetAnimationDuration ??
                const Duration(milliseconds: 100),
            insetAnimationCurve: insetAnimationCurve ??
                flashTheme.insetAnimationCurve ??
                Curves.fastOutSlowIn,
            forwardAnimationCurve: forwardAnimationCurve ??
                flashTheme.forwardAnimationCurve ??
                Curves.fastOutSlowIn,
            reverseAnimationCurve: reverseAnimationCurve ??
                flashTheme.reverseAnimationCurve ??
                Curves.fastOutSlowIn,
            onTap: onTap == null
                ? null
                : () => onTap(context, controller, setState),
            child: child,
          );
        });
      },
    );
    dismissCompleter?.future.then(controller.dismiss);
    return controller.show();
  }
}

/// A builder that creates a widget given the `controller` and `setState`.
typedef FlashWidgetBuilder<T> = Widget Function(
    BuildContext context, FlashController<T> controller, StateSetter setState);

/// Signature of callbacks that have arguments of flash and return no data.
typedef FlashCallback<T> = void Function(
    BuildContext context, FlashController<T> controller, StateSetter setState);

/// Flash bar type.
enum FlashBarType {
  /// Information type to use [FlashBarThemeData.infoColor].
  info,

  /// Success type to use [FlashBarThemeData.successColor].
  success,

  /// Success type to use [FlashBarThemeData.errorColor].
  error,
}

/// Applies a theme to descendant flash widgets.
class FlashTheme extends InheritedWidget {
  /// Specifies the styles for descendant flash bar widgets.
  final FlashBarThemeData? flashBarTheme;

  /// Specifies the style for descendant flash dialog widgets.
  final FlashDialogThemeData? flashDialogTheme;

  /// Specifies the style for descendant flash block dialog widgets.
  final FlashBlockDialogThemeData? flashBlockDialogTheme;

  /// Applies the given theme to [child].
  FlashTheme({
    Key? key,
    required Widget child,
    this.flashBarTheme,
    this.flashDialogTheme,
    this.flashBlockDialogTheme,
  }) : super(key: key, child: child);

  /// The data from the closest [FlashBarThemeData] instance given the build
  /// context.
  static FlashBarThemeData bar(BuildContext context) {
    final flashTheme = context.dependOnInheritedWidgetOfExactType<FlashTheme>();
    final theme = Theme.of(context);
    final isThemeDark = theme.brightness == Brightness.dark;
    return flashTheme?.flashBarTheme ??
        (isThemeDark
            ? const FlashBarThemeData.dark()
            : const FlashBarThemeData.light());
  }

  /// The data from the closest [FlashDialogThemeData] instance given the build
  /// context.
  static FlashDialogThemeData dialog(BuildContext context) {
    final flashTheme = context.dependOnInheritedWidgetOfExactType<FlashTheme>();
    return flashTheme?.flashDialogTheme ?? const FlashDialogThemeData();
  }

  /// The data from the closest [FlashBlockDialogThemeData] instance given the
  /// build context.
  static FlashBlockDialogThemeData blockDialog(BuildContext context) {
    final flashTheme = context.dependOnInheritedWidgetOfExactType<FlashTheme>();
    return flashTheme?.flashBlockDialogTheme ??
        const FlashBlockDialogThemeData();
  }

  @override
  bool updateShouldNotify(covariant FlashTheme oldWidget) {
    return flashBarTheme != oldWidget.flashBarTheme ||
        flashDialogTheme != oldWidget.flashDialogTheme ||
        flashBlockDialogTheme != oldWidget.flashBlockDialogTheme;
  }
}

/// Defines the configuration of the overall visual [FlashTheme] bar.
@immutable
class FlashBarThemeData {
  /// Default value for [FlashController.transitionDuration].
  final Duration? transitionDuration;

  /// Default value for [Flash.behavior].
  ///
  /// If null, [Flash] will default to [FlashBehavior.fixed].
  final FlashBehavior? behavior;

  /// Default value for [Flash.position].
  ///
  /// If null, [Flash] will default to [FlashPosition.bottom].
  final FlashPosition? position;

  /// Default value for [Flash.enableVerticalDrag].
  ///
  /// If null, [Flash] will default to true.
  final bool? enableVerticalDrag;

  /// Default value for [Flash.horizontalDismissDirection].
  final HorizontalDismissDirection? horizontalDismissDirection;

  /// Default value for [Flash.brightness].
  ///
  /// If null, [Flash] will default to inversion of [ThemeData.brightness].
  final Brightness? brightness;

  /// Default value for [Flash.backgroundColor].
  ///
  /// If null, [Flash] will default to inversion of [ThemeData.backgroundColor].
  final Color? backgroundColor;

  /// Default value for [Flash.backgroundGradient].
  final Gradient? backgroundGradient;

  /// Default value for [Flash.boxShadows].
  final List<BoxShadow>? boxShadows;

  /// Default value for [Flash.barrierBlur].
  final double? barrierBlur;

  /// Default value for [Flash.barrierColor].
  final Color? barrierColor;

  /// Default value for [Flash.barrierDismissible].
  ///
  /// If null, [Flash] will default to true.
  final bool? barrierDismissible;

  /// Default value for [Flash.borderRadius].
  final BorderRadius? borderRadius;

  /// Default value for [Flash.borderColor].
  final Color? borderColor;

  /// Default value for [Flash.borderWidth].
  final double? borderWidth;

  /// Default value for [Flash.constraints].
  final BoxConstraints? constraints;

  /// Default value for [Flash.margin].
  ///
  /// If null, [Flash] will default to [EdgeInsets.zero].
  final EdgeInsets? margin;

  /// Default value for [Flash.insetAnimationDuration].
  ///
  /// If null, [Flash] will default to 100 ms.
  final Duration? insetAnimationDuration;

  /// Default value for [Flash.insetAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? insetAnimationCurve;

  /// Default value for [Flash.forwardAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? forwardAnimationCurve;

  /// Default value for [Flash.reverseAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? reverseAnimationCurve;

  /// Default value for [FlashBar.padding].
  final EdgeInsets? padding;

  /// Default style for [FlashBar.title].
  final TextStyle? titleStyle;

  /// Default style for [FlashBar.content].
  final TextStyle? contentStyle;

  /// Default style for [FlashBar.primaryAction] and [FlashBar.actions].
  final Color? actionColor;

  /// Default value for [FlashBar.indicatorColor] when [FlashBarType.info] used.
  final Color? infoColor;

  /// Default value for [FlashBar.indicatorColor] when [FlashBarType.success]
  /// used.
  final Color? successColor;

  /// Default value for [FlashBar.indicatorColor] when [FlashBarType.error]
  /// used.
  final Color? errorColor;

  /// Creates a flash bar theme that can be used for [Flash] bar.
  const FlashBarThemeData({
    this.transitionDuration = const Duration(milliseconds: 500),
    this.behavior,
    this.position,
    this.enableVerticalDrag,
    this.horizontalDismissDirection,
    this.brightness,
    this.backgroundColor,
    this.backgroundGradient,
    this.boxShadows,
    this.barrierBlur,
    this.barrierColor,
    this.barrierDismissible,
    this.borderRadius,
    this.borderColor,
    this.borderWidth,
    this.constraints,
    this.margin,
    this.insetAnimationDuration,
    this.insetAnimationCurve,
    this.forwardAnimationCurve,
    this.reverseAnimationCurve,
    this.padding,
    this.titleStyle,
    this.contentStyle,
    this.actionColor,
    this.infoColor,
    this.successColor,
    this.errorColor,
  });

  /// A default light theme.
  const FlashBarThemeData.light()
      : this(
    brightness: Brightness.light,
    infoColor: const Color(0xFF64B5F6),
    successColor: const Color(0xFF81C784),
    errorColor: const Color(0xFFE57373),
  );

  /// A default dark theme.
  const FlashBarThemeData.dark()
      : this(
    brightness: Brightness.dark,
    infoColor: const Color(0xFF42A5F5),
    successColor: const Color(0xFF66BB6A),
    errorColor: const Color(0xFFEF5350),
  );

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
          other is FlashBarThemeData &&
              runtimeType == other.runtimeType &&
              transitionDuration == other.transitionDuration &&
              behavior == other.behavior &&
              position == other.position &&
              enableVerticalDrag == other.enableVerticalDrag &&
              horizontalDismissDirection == other.horizontalDismissDirection &&
              brightness == other.brightness &&
              backgroundColor == other.backgroundColor &&
              backgroundGradient == other.backgroundGradient &&
              boxShadows == other.boxShadows &&
              barrierBlur == other.barrierBlur &&
              barrierColor == other.barrierColor &&
              barrierDismissible == other.barrierDismissible &&
              borderRadius == other.borderRadius &&
              borderColor == other.borderColor &&
              borderWidth == other.borderWidth &&
              constraints == other.constraints &&
              margin == other.margin &&
              insetAnimationDuration == other.insetAnimationDuration &&
              insetAnimationCurve == other.insetAnimationCurve &&
              forwardAnimationCurve == other.forwardAnimationCurve &&
              reverseAnimationCurve == other.reverseAnimationCurve &&
              padding == other.padding &&
              titleStyle == other.titleStyle &&
              contentStyle == other.contentStyle &&
              actionColor == other.actionColor &&
              infoColor == other.infoColor &&
              successColor == other.successColor &&
              errorColor == other.errorColor;

  @override
  int get hashCode =>
      transitionDuration.hashCode ^
      behavior.hashCode ^
      position.hashCode ^
      enableVerticalDrag.hashCode ^
      horizontalDismissDirection.hashCode ^
      brightness.hashCode ^
      backgroundColor.hashCode ^
      backgroundGradient.hashCode ^
      boxShadows.hashCode ^
      barrierBlur.hashCode ^
      barrierColor.hashCode ^
      barrierDismissible.hashCode ^
      borderRadius.hashCode ^
      borderColor.hashCode ^
      borderWidth.hashCode ^
      constraints.hashCode ^
      margin.hashCode ^
      insetAnimationDuration.hashCode ^
      insetAnimationCurve.hashCode ^
      forwardAnimationCurve.hashCode ^
      reverseAnimationCurve.hashCode ^
      padding.hashCode ^
      titleStyle.hashCode ^
      contentStyle.hashCode ^
      actionColor.hashCode ^
      infoColor.hashCode ^
      successColor.hashCode ^
      errorColor.hashCode;

  FlashBarThemeData copyWith({
    Duration? transitionDuration,
    FlashBehavior? behavior,
    FlashPosition? position,
    bool? enableVerticalDrag,
    HorizontalDismissDirection? horizontalDismissDirection,
    Brightness? brightness,
    Color? backgroundColor,
    Gradient? backgroundGradient,
    List<BoxShadow>? boxShadows,
    double? barrierBlur,
    Color? barrierColor,
    bool? barrierDismissible,
    BorderRadius? borderRadius,
    Color? borderColor,
    double? borderWidth,
    BoxConstraints? constraints,
    EdgeInsets? margin,
    Duration? insetAnimationDuration,
    Curve? insetAnimationCurve,
    Curve? forwardAnimationCurve,
    Curve? reverseAnimationCurve,
    EdgeInsets? padding,
    TextStyle? titleStyle,
    TextStyle? contentStyle,
    Color? actionColor,
    Color? infoColor,
    Color? successColor,
    Color? errorColor,
  }) {
    return FlashBarThemeData(
      transitionDuration: transitionDuration ?? this.transitionDuration,
      behavior: behavior ?? this.behavior,
      position: position ?? this.position,
      enableVerticalDrag: enableVerticalDrag ?? this.enableVerticalDrag,
      horizontalDismissDirection:
      horizontalDismissDirection ?? this.horizontalDismissDirection,
      brightness: brightness ?? this.brightness,
      backgroundColor: backgroundColor ?? this.backgroundColor,
      backgroundGradient: backgroundGradient ?? this.backgroundGradient,
      boxShadows: boxShadows ?? this.boxShadows,
      barrierBlur: barrierBlur ?? this.barrierBlur,
      barrierColor: barrierColor ?? this.barrierColor,
      barrierDismissible: barrierDismissible ?? this.barrierDismissible,
      borderRadius: borderRadius ?? this.borderRadius,
      borderColor: borderColor ?? this.borderColor,
      borderWidth: borderWidth ?? this.borderWidth,
      constraints: constraints ?? this.constraints,
      margin: margin ?? this.margin,
      insetAnimationDuration:
      insetAnimationDuration ?? this.insetAnimationDuration,
      insetAnimationCurve: insetAnimationCurve ?? this.insetAnimationCurve,
      forwardAnimationCurve:
      forwardAnimationCurve ?? this.forwardAnimationCurve,
      reverseAnimationCurve:
      reverseAnimationCurve ?? this.reverseAnimationCurve,
      padding: padding ?? this.padding,
      titleStyle: titleStyle ?? this.titleStyle,
      contentStyle: contentStyle ?? this.contentStyle,
      actionColor: actionColor ?? this.actionColor,
      infoColor: infoColor ?? this.infoColor,
      successColor: successColor ?? this.successColor,
      errorColor: errorColor ?? this.errorColor,
    );
  }
}

/// Defines the configuration of the overall visual [FlashTheme] dialog.
@immutable
class FlashDialogThemeData {
  /// Default value for [FlashController.transitionDuration].
  final Duration? transitionDuration;

  /// Default value for [Flash.brightness].
  ///
  /// If null, [Flash] will default to [ThemeData.brightness].
  final Brightness? brightness;

  /// Default value for [Flash.backgroundColor].
  ///
  /// If null, [Flash] will default to [DialogTheme.backgroundColor] or
  /// [ThemeData.dialogBackgroundColor].
  final Color? backgroundColor;

  /// Default value for [Flash.backgroundGradient].
  final Gradient? backgroundGradient;

  /// Default value for [Flash.boxShadows].
  final List<BoxShadow>? boxShadows;

  /// Default value for [Flash.barrierBlur].
  final double? barrierBlur;

  /// Default value for [Flash.barrierColor].
  final Color? barrierColor;

  /// Default value for [Flash.barrierDismissible].
  ///
  /// If null, [Flash] will default to true.
  final bool? barrierDismissible;

  /// Default value for [Flash.borderRadius].
  final BorderRadius? borderRadius;

  /// Default value for [Flash.borderColor].
  final Color? borderColor;

  /// Default value for [Flash.borderWidth].
  final double? borderWidth;

  /// Default value for [Flash.constraints].
  final BoxConstraints? constraints;

  /// Default value for [Flash.margin].
  final EdgeInsets? margin;

  /// Default value for [Flash.insetAnimationDuration].
  ///
  /// If null, [Flash] will default to 100 ms.
  final Duration? insetAnimationDuration;

  /// Default value for [Flash.insetAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? insetAnimationCurve;

  /// Default value for [Flash.forwardAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? forwardAnimationCurve;

  /// Default value for [Flash.reverseAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? reverseAnimationCurve;

  /// Default value for [FlashBar.padding].
  final EdgeInsets? padding;

  /// Default style for [FlashBar.title].
  final TextStyle? titleStyle;

  /// Default style for [FlashBar.content].
  final TextStyle? contentStyle;

  /// Default style for [FlashBar.actions].
  final Color? actionColor;

  /// Creates a flash dialog theme that can be used for [Flash] dialog.
  const FlashDialogThemeData({
    this.transitionDuration = const Duration(milliseconds: 500),
    this.brightness,
    this.backgroundColor,
    this.backgroundGradient,
    this.boxShadows,
    this.barrierBlur,
    this.barrierColor = Colors.black54,
    this.barrierDismissible,
    this.borderRadius = const BorderRadius.all(Radius.circular(8.0)),
    this.borderColor,
    this.borderWidth,
    this.constraints,
    this.margin,
    this.insetAnimationDuration,
    this.reverseAnimationCurve,
    this.insetAnimationCurve,
    this.forwardAnimationCurve,
    this.padding,
    this.titleStyle,
    this.contentStyle,
    this.actionColor,
  });

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
          other is FlashDialogThemeData &&
              runtimeType == other.runtimeType &&
              transitionDuration == other.transitionDuration &&
              brightness == other.brightness &&
              backgroundColor == other.backgroundColor &&
              backgroundGradient == other.backgroundGradient &&
              boxShadows == other.boxShadows &&
              barrierBlur == other.barrierBlur &&
              barrierColor == other.barrierColor &&
              barrierDismissible == other.barrierDismissible &&
              borderRadius == other.borderRadius &&
              borderColor == other.borderColor &&
              borderWidth == other.borderWidth &&
              constraints == other.constraints &&
              margin == other.margin &&
              insetAnimationDuration == other.insetAnimationDuration &&
              insetAnimationCurve == other.insetAnimationCurve &&
              forwardAnimationCurve == other.forwardAnimationCurve &&
              reverseAnimationCurve == other.reverseAnimationCurve &&
              padding == other.padding &&
              titleStyle == other.titleStyle &&
              contentStyle == other.contentStyle &&
              actionColor == other.actionColor;

  @override
  int get hashCode =>
      transitionDuration.hashCode ^
      brightness.hashCode ^
      backgroundColor.hashCode ^
      backgroundGradient.hashCode ^
      boxShadows.hashCode ^
      barrierBlur.hashCode ^
      barrierColor.hashCode ^
      barrierDismissible.hashCode ^
      borderRadius.hashCode ^
      borderColor.hashCode ^
      borderWidth.hashCode ^
      constraints.hashCode ^
      margin.hashCode ^
      insetAnimationDuration.hashCode ^
      insetAnimationCurve.hashCode ^
      forwardAnimationCurve.hashCode ^
      reverseAnimationCurve.hashCode ^
      padding.hashCode ^
      titleStyle.hashCode ^
      contentStyle.hashCode ^
      actionColor.hashCode;

  FlashDialogThemeData copyWith({
    Duration? transitionDuration,
    Brightness? brightness,
    Color? backgroundColor,
    Gradient? backgroundGradient,
    List<BoxShadow>? boxShadows,
    double? barrierBlur,
    Color? barrierColor,
    bool? barrierDismissible,
    BorderRadius? borderRadius,
    Color? borderColor,
    double? borderWidth,
    BoxConstraints? constraints,
    EdgeInsets? margin,
    Duration? insetAnimationDuration,
    Curve? insetAnimationCurve,
    Curve? forwardAnimationCurve,
    Curve? reverseAnimationCurve,
    EdgeInsets? padding,
    TextStyle? titleStyle,
    TextStyle? contentStyle,
    Color? actionColor,
  }) {
    return FlashDialogThemeData(
      transitionDuration: transitionDuration ?? this.transitionDuration,
      brightness: brightness ?? this.brightness,
      backgroundColor: backgroundColor ?? this.backgroundColor,
      backgroundGradient: backgroundGradient ?? this.backgroundGradient,
      boxShadows: boxShadows ?? this.boxShadows,
      barrierBlur: barrierBlur ?? this.barrierBlur,
      barrierColor: barrierColor ?? this.barrierColor,
      barrierDismissible: barrierDismissible ?? this.barrierDismissible,
      borderRadius: borderRadius ?? this.borderRadius,
      borderColor: borderColor ?? this.borderColor,
      borderWidth: borderWidth ?? this.borderWidth,
      constraints: constraints ?? this.constraints,
      margin: margin ?? this.margin,
      insetAnimationDuration:
      insetAnimationDuration ?? this.insetAnimationDuration,
      insetAnimationCurve: insetAnimationCurve ?? this.insetAnimationCurve,
      forwardAnimationCurve:
      forwardAnimationCurve ?? this.forwardAnimationCurve,
      reverseAnimationCurve:
      reverseAnimationCurve ?? this.reverseAnimationCurve,
      padding: padding ?? this.padding,
      titleStyle: titleStyle ?? this.titleStyle,
      contentStyle: contentStyle ?? this.contentStyle,
      actionColor: actionColor ?? this.actionColor,
    );
  }
}

/// Defines the configuration of the overall visual [FlashTheme] dialog.
@immutable
class FlashBlockDialogThemeData {
  /// Default value for [FlashController.transitionDuration].
  final Duration? transitionDuration;

  /// Default value for [Flash.brightness].
  ///
  /// If null, [Flash] will default to [ThemeData.brightness].
  final Brightness? brightness;

  /// Default value for [Flash.backgroundColor].
  ///
  /// If null, [Flash] will default to [DialogTheme.backgroundColor] or
  /// [ThemeData.dialogBackgroundColor].
  final Color? backgroundColor;

  /// Default value for [Flash.backgroundGradient].
  final Gradient? backgroundGradient;

  /// Default value for [Flash.boxShadows].
  final List<BoxShadow>? boxShadows;

  /// Default value for [Flash.barrierBlur].
  final double? barrierBlur;

  /// Default value for [Flash.barrierColor].
  final Color? barrierColor;

  /// Default value for [Flash.borderRadius].
  final BorderRadius? borderRadius;

  /// Default value for [Flash.borderColor].
  final Color? borderColor;

  /// Default value for [Flash.borderWidth].
  final double? borderWidth;

  /// Default value for [Flash.margin].
  final EdgeInsets? margin;

  /// Default value for [Flash.insetAnimationDuration].
  ///
  /// If null, [Flash] will default to 100 ms.
  final Duration? insetAnimationDuration;

  /// Default value for [Flash.insetAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? insetAnimationCurve;

  /// Default value for [Flash.forwardAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? forwardAnimationCurve;

  /// Default value for [Flash.reverseAnimationCurve].
  ///
  /// If null, [Flash] will default to [Curves.fastOutSlowIn].
  final Curve? reverseAnimationCurve;

  /// Default value for [FlashBar.padding].
  final EdgeInsets? padding;

  /// Creates a flash dialog theme that can be used for [Flash] block dialog.
  const FlashBlockDialogThemeData({
    this.transitionDuration = const Duration(milliseconds: 500),
    this.brightness,
    this.backgroundColor,
    this.backgroundGradient,
    this.boxShadows,
    this.barrierBlur,
    this.barrierColor = Colors.black54,
    this.borderRadius = const BorderRadius.all(Radius.circular(8.0)),
    this.borderColor,
    this.borderWidth,
    this.margin,
    this.insetAnimationDuration,
    this.reverseAnimationCurve,
    this.insetAnimationCurve,
    this.forwardAnimationCurve,
    this.padding,
  });

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
          other is FlashBlockDialogThemeData &&
              runtimeType == other.runtimeType &&
              transitionDuration == other.transitionDuration &&
              brightness == other.brightness &&
              backgroundColor == other.backgroundColor &&
              backgroundGradient == other.backgroundGradient &&
              boxShadows == other.boxShadows &&
              barrierBlur == other.barrierBlur &&
              barrierColor == other.barrierColor &&
              borderRadius == other.borderRadius &&
              borderColor == other.borderColor &&
              borderWidth == other.borderWidth &&
              margin == other.margin &&
              insetAnimationDuration == other.insetAnimationDuration &&
              insetAnimationCurve == other.insetAnimationCurve &&
              forwardAnimationCurve == other.forwardAnimationCurve &&
              reverseAnimationCurve == other.reverseAnimationCurve &&
              padding == other.padding;

  @override
  int get hashCode =>
      transitionDuration.hashCode ^
      brightness.hashCode ^
      backgroundColor.hashCode ^
      backgroundGradient.hashCode ^
      boxShadows.hashCode ^
      barrierBlur.hashCode ^
      barrierColor.hashCode ^
      borderRadius.hashCode ^
      borderColor.hashCode ^
      borderWidth.hashCode ^
      margin.hashCode ^
      insetAnimationDuration.hashCode ^
      insetAnimationCurve.hashCode ^
      forwardAnimationCurve.hashCode ^
      reverseAnimationCurve.hashCode ^
      padding.hashCode;

  FlashBlockDialogThemeData copyWith({
    Duration? transitionDuration,
    Brightness? brightness,
    Color? backgroundColor,
    Gradient? backgroundGradient,
    List<BoxShadow>? boxShadows,
    double? barrierBlur,
    Color? barrierColor,
    BorderRadius? borderRadius,
    Color? borderColor,
    double? borderWidth,
    EdgeInsets? margin,
    Duration? insetAnimationDuration,
    Curve? insetAnimationCurve,
    Curve? forwardAnimationCurve,
    Curve? reverseAnimationCurve,
    EdgeInsets? padding,
  }) {
    return FlashBlockDialogThemeData(
      transitionDuration: transitionDuration ?? this.transitionDuration,
      brightness: brightness ?? this.brightness,
      backgroundColor: backgroundColor ?? this.backgroundColor,
      backgroundGradient: backgroundGradient ?? this.backgroundGradient,
      boxShadows: boxShadows ?? this.boxShadows,
      barrierBlur: barrierBlur ?? this.barrierBlur,
      barrierColor: barrierColor ?? this.barrierColor,
      borderRadius: borderRadius ?? this.borderRadius,
      borderColor: borderColor ?? this.borderColor,
      borderWidth: borderWidth ?? this.borderWidth,
      margin: margin ?? this.margin,
      insetAnimationDuration:
      insetAnimationDuration ?? this.insetAnimationDuration,
      insetAnimationCurve: insetAnimationCurve ?? this.insetAnimationCurve,
      forwardAnimationCurve:
      forwardAnimationCurve ?? this.forwardAnimationCurve,
      reverseAnimationCurve:
      reverseAnimationCurve ?? this.reverseAnimationCurve,
      padding: padding ?? this.padding,
    );
  }
}


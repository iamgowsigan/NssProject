import 'dart:async';
import 'package:flutter/material.dart';
import 'package:nss/utils/style_sheet.dart';

class ListAnimation extends StatelessWidget {
  int duration=800;
  bool horizontal=true;
  bool vertical=false;
  List<Widget> children;
  double runner=0;
  double spacer=0;
  bool isRow=false;

  ListAnimation({Key? key, required this.children, this.duration=800,this.horizontal=true,this.vertical=false,this.runner=0,this.spacer=0,this.isRow=false}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AnimationLimiter(
        child:Container(
          width: Width(context),
          child: (isRow==false)? Wrap(
            direction: Axis.horizontal,
            alignment: WrapAlignment.start,
            runSpacing: runner,
            spacing: spacer,

            children: AnimationConfiguration.toStaggeredList(
                duration: Duration(milliseconds: duration),
                childAnimationBuilder: (widget) =>
                    SlideAnimation(
                      horizontalOffset: (horizontal==true)?MediaQuery.of(context).size.width * 0.8:null,
                      verticalOffset: (vertical==true)?MediaQuery.of(context).size.height * 0.8:null,
                      child: FadeInAnimation(child: widget),
                    ),
                children: children
            ),
          ):SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            child: Row(
              children: AnimationConfiguration.toStaggeredList(
                  duration: Duration(milliseconds: duration),
                  childAnimationBuilder: (widget) =>
                      SlideAnimation(
                        horizontalOffset: (horizontal==true)?MediaQuery.of(context).size.width * 0.8:null,
                        verticalOffset: (vertical==true)?MediaQuery.of(context).size.height * 0.8:null,
                        child: FadeInAnimation(child: widget),
                      ),
                  children: children
              ),
            ),
          ),
        )
    );
  }
}


class AnimationLimiter extends StatefulWidget {
  /// The child Widget to animate.
  final Widget child;

  /// Creates an [AnimationLimiter] that will prevents the children widgets to be
  /// animated if they don't appear in the first frame where AnimationLimiter is built.
  ///
  /// The [child] argument must not be null.
  const AnimationLimiter({
    Key? key,
    required this.child,
  }) : super(key: key);

  @override
  _AnimationLimiterState createState() => _AnimationLimiterState();

  static bool? shouldRunAnimation(BuildContext context) {
    return _AnimationLimiterProvider.of(context)?.shouldRunAnimation;
  }
}

class _AnimationLimiterState extends State<AnimationLimiter> {
  bool _shouldRunAnimation = true;

  @override
  void initState() {
    super.initState();

    WidgetsBinding.instance.addPostFrameCallback((Duration value) {
      if (!mounted) return;
      setState(() {
        _shouldRunAnimation = false;
      });
    });
  }

  @override
  Widget build(BuildContext context) {
    return _AnimationLimiterProvider(
      shouldRunAnimation: _shouldRunAnimation,
      child: widget.child,
    );
  }
}

class _AnimationLimiterProvider extends InheritedWidget {
  final bool? shouldRunAnimation;

  _AnimationLimiterProvider({
    this.shouldRunAnimation,
    required Widget child,
  }) : super(child: child);

  @override
  bool updateShouldNotify(InheritedWidget oldWidget) {
    return false;
  }

  static _AnimationLimiterProvider? of(BuildContext context) {
    return context.findAncestorWidgetOfExactType<_AnimationLimiterProvider>();
  }
}

class AnimationConfiguration extends InheritedWidget {
  /// Index used as a factor to calculate the delay of each child's animation.
  final int position;

  /// The duration of each child's animation.
  final Duration duration;

  /// The delay between the beginning of two children's animations.
  final Duration? delay;

  /// The column count of the grid
  final int columnCount;

  /// Configure the children's animation to be synchronized (all the children's animation start at the same time).
  ///
  /// Default value for [duration] is 225ms.
  ///
  /// The [child] argument must not be null.
  const AnimationConfiguration.synchronized({
    Key? key,
    this.duration = const Duration(milliseconds: 225),
    required Widget child,
  })   : position = 0,
        delay = Duration.zero,
        columnCount = 1,
        super(key: key, child: child);

  /// Configure the children's animation to be staggered.
  ///
  /// A staggered animation consists of sequential or overlapping animations.
  ///
  /// Each child animation will start with a delay based on its position comparing to previous children.
  ///
  /// The staggering effect will be based on a single axis (from top to bottom or from left to right).
  ///
  /// Use this named constructor to display a staggered animation on a single-axis list of widgets
  /// ([ListView], [ScrollView], [Column], [Row]...).
  ///
  /// The [position] argument must not be null.
  ///
  /// Default value for [duration] is 225ms.
  ///
  /// Default value for [delay] is the [duration] divided by 6
  /// (appropriate factor to keep coherence during the animation).
  ///
  /// The [child] argument must not be null.
  const AnimationConfiguration.staggeredList({
    Key? key,
    required this.position,
    this.duration = const Duration(milliseconds: 225),
    this.delay,
    required Widget child,
  })   : columnCount = 1,
        super(key: key, child: child);

  /// Configure the children's animation to be staggered.
  ///
  /// A staggered animation consists of sequential or overlapping animations.
  ///
  /// Each child animation will start with a delay based on its position comparing to previous children.
  ///
  /// The staggering effect will be based on a dual-axis (from left to right and top to bottom).
  ///
  /// Use this named constructor to display a staggered animation on a dual-axis list of widgets
  /// ([GridView]...).
  ///
  /// The [position] argument must not be null.
  ///
  /// Default value for [duration] is 225ms.
  ///
  /// Default value for [delay] is the [duration] divided by 6
  /// (appropriate factor to keep coherence during the animation).
  ///
  /// The [columnCount] argument must not be null and must be greater than 0.
  ///
  /// The [child] argument must not be null.
  const AnimationConfiguration.staggeredGrid({
    Key? key,
    required this.position,
    this.duration = const Duration(milliseconds: 225),
    this.delay,
    required this.columnCount,
    required Widget child,
  }) : super(key: key, child: child);

  @override
  bool updateShouldNotify(InheritedWidget oldWidget) {
    return false;
  }

  /// Helper method to apply a staggered animation to the children of a [Column] or [Row].
  ///
  /// It maps every child with an index and calls
  /// [AnimationConfiguration.staggeredList] constructor under the hood.
  ///
  /// Default value for [duration] is 225ms.
  ///
  /// Default value for [delay] is the [duration] divided by 6
  /// (appropriate factor to keep coherence during the animation).
  ///
  /// The [childAnimationBuilder] is a function that will be applied to each child you provide in [children]
  ///
  /// The following is an example of a [childAnimationBuilder] you could provide:
  ///
  /// ```dart
  /// (widget) => SlideAnimation(
  ///   horizontalOffset: 50.0,
  ///   child: FadeInAnimation(
  ///     child: widget,
  ///   ),
  /// )
  /// ```
  ///
  /// The [children] argument must not be null.
  /// It corresponds to the children you would normally have passed to the [Column] or [Row].
  static List<Widget> toStaggeredList({
    Duration? duration,
    Duration? delay,
    required Widget Function(Widget) childAnimationBuilder,
    required List<Widget> children,
  }) =>
      children
          .asMap()
          .map((index, widget) {
        return MapEntry(
          index,
          AnimationConfiguration.staggeredList(
            position: index,
            duration: duration ?? const Duration(milliseconds: 225),
            delay: delay,
            child: childAnimationBuilder(widget),
          ),
        );
      })
          .values
          .toList();

  static AnimationConfiguration? of(BuildContext context) {
    return context.findAncestorWidgetOfExactType<AnimationConfiguration>();
  }
}

class SlideAnimation extends StatelessWidget {
  /// The duration of the child animation.
  final Duration? duration;

  /// The delay between the beginning of two children's animations.
  final Duration? delay;

  /// The curve of the child animation. Defaults to [Curves.ease].
  final Curve curve;

  /// The vertical offset to apply at the start of the animation (can be negative).
  final double verticalOffset;

  /// The horizontal offset to apply at the start of the animation (can be negative).
  final double horizontalOffset;

  /// The child Widget to animate.
  final Widget child;

  /// Creates a slide animation that slides its child from the given
  /// [verticalOffset] and [horizontalOffset] to its final position.
  ///
  /// A default value of 50.0 is applied to [verticalOffset] if
  /// [verticalOffset] and [horizontalOffset] are both undefined or null.
  ///
  /// The [child] argument must not be null.
  const SlideAnimation({
    Key? key,
    this.duration,
    this.delay,
    this.curve = Curves.ease,
    double? verticalOffset,
    double? horizontalOffset,
    required this.child,
  })   : verticalOffset = (verticalOffset == null && horizontalOffset == null) ? 50.0 : (verticalOffset ?? 0.0),
        horizontalOffset = horizontalOffset ?? 0.0,
        super(key: key);

  @override
  Widget build(BuildContext context) {
    return AnimationConfigurator(
      duration: duration,
      delay: delay,
      animatedChildBuilder: _slideAnimation,
    );
  }

  Widget _slideAnimation(Animation<double> animation) {
    Animation<double> offsetAnimation(double offset, Animation<double> animation) {
      return Tween<double>(begin: offset, end: 0.0).animate(
        CurvedAnimation(
          parent: animation,
          curve: Interval(0.0, 1.0, curve: curve),
        ),
      );
    }

    return Transform.translate(
      offset: Offset(
        horizontalOffset == 0.0 ? 0.0 : offsetAnimation(horizontalOffset, animation).value,
        verticalOffset == 0.0 ? 0.0 : offsetAnimation(verticalOffset, animation).value,
      ),
      child: child,
    );
  }
}

class AnimationConfigurator extends StatelessWidget {
  final Duration? duration;
  final Duration? delay;
  final Widget Function(Animation<double>) animatedChildBuilder;

  const AnimationConfigurator({
    Key? key,
    this.duration,
    this.delay,
    required this.animatedChildBuilder,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    final animationConfiguration = AnimationConfiguration.of(context);

    if (animationConfiguration == null) {
      throw FlutterError.fromParts(
        <DiagnosticsNode>[
          ErrorSummary('Animation not wrapped in an AnimationConfiguration.'),
          ErrorDescription(
              'This error happens if you use an Animation that is not wrapped in an '
                  'AnimationConfiguration.'),
          ErrorHint(
              'The solution is to wrap your Animation(s) with an AnimationConfiguration. '
                  'Reminder: an AnimationConfiguration provides the configuration '
                  'used as a base for every children Animation. Configuration made in AnimationConfiguration '
                  'can be overridden in Animation children if needed.'),
        ],
      );
    }

    final _position = animationConfiguration.position;
    final _duration = duration ?? animationConfiguration.duration;
    final _delay = delay ?? animationConfiguration.delay;
    final _columnCount = animationConfiguration.columnCount;

    return AnimationExecutor(
      duration: _duration,
      delay: stagger(_position, _duration, _delay, _columnCount),
      builder: (context, animationController) =>
          animatedChildBuilder(animationController!),
    );
  }

  Duration stagger(
      int position, Duration duration, Duration? delay, int columnCount) {
    var delayInMilliseconds =
    (delay == null ? duration.inMilliseconds ~/ 6 : delay.inMilliseconds);

    int _computeStaggeredGridDuration() {
      return (position ~/ columnCount + position % columnCount) *
          delayInMilliseconds;
    }

    int _computeStaggeredListDuration() {
      return position * delayInMilliseconds;
    }

    return Duration(
        milliseconds: columnCount > 1
            ? _computeStaggeredGridDuration()
            : _computeStaggeredListDuration());
  }
}

typedef Builder = Widget Function(BuildContext context, AnimationController? animationController);

class AnimationExecutor extends StatefulWidget {
  final Duration duration;
  final Duration delay;
  final Builder builder;

  const AnimationExecutor({
    Key? key,
    required this.duration,
    this.delay = Duration.zero,
    required this.builder,
  }) : super(key: key);

  @override
  _AnimationExecutorState createState() => _AnimationExecutorState();
}

class _AnimationExecutorState extends State<AnimationExecutor> with SingleTickerProviderStateMixin {
  late final AnimationController _animationController;
  Timer? _timer;

  @override
  void initState() {
    super.initState();

    _animationController =
        AnimationController(duration: widget.duration, vsync: this);

    if (AnimationLimiter.shouldRunAnimation(context) ?? true) {
      _timer = Timer(widget.delay, _animationController.forward);
    } else {
      _animationController.value = 1.0;
    }
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      builder: _buildAnimation,
      animation: _animationController,
    );
  }

  @override
  void dispose() {
    _timer?.cancel();
    _animationController.dispose();
    super.dispose();
  }

  Widget _buildAnimation(BuildContext context, Widget? child) {
    return widget.builder(context, _animationController);
  }
}

class FadeInAnimation extends StatelessWidget {
  /// The duration of the child animation.
  final Duration? duration;

  /// The delay between the beginning of two children's animations.
  final Duration? delay;

  /// The curve of the child animation. Defaults to [Curves.ease].
  final Curve curve;

  /// The child Widget to animate.
  final Widget child;

  /// Creates a fade animation that fades its child.
  ///
  /// The [child] argument must not be null.
  const FadeInAnimation({
    Key? key,
    this.duration,
    this.delay,
    this.curve = Curves.ease,
    required this.child,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AnimationConfigurator(
      duration: duration,
      delay: delay,
      animatedChildBuilder: _fadeInAnimation,
    );
  }

  Widget _fadeInAnimation(Animation<double> animation) {
    final _opacityAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
      CurvedAnimation(
        parent: animation,
        curve: Interval(0.0, 1.0, curve: curve),
      ),
    );

    return Opacity(
      opacity: _opacityAnimation.value,
      child: child,
    );
  }
}
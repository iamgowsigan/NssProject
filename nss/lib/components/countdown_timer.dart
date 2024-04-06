import 'package:flutter/material.dart';
import 'package:flutter/widgets.dart';
import 'dart:async';
import 'package:flutter/animation.dart';
import 'dart:math';

typedef CountdownTimerWidgetBuilder = Widget Function(
    BuildContext context, CurrentRemainingTime? time);

/// A Countdown.
class CountdownTimer extends StatefulWidget {
  ///Widget displayed after the countdown
  final Widget endWidget;
  ///Used to customize the countdown style widget
  final CountdownTimerWidgetBuilder? widgetBuilder;
  ///Countdown controller, can end the countdown event early
  final CountdownTimerController? controller;
  ///Countdown text style
  final TextStyle? textStyle;
  ///Event called after the countdown ends
  final VoidCallback? onEnd;
  ///The end time of the countdown.
  final int? endTime;

  CountdownTimer({
    Key? key,
    this.endWidget = const Center(
      child: Text('The current time has expired'),
    ),
    this.widgetBuilder,
    this.controller,
    this.textStyle,
    this.endTime,
    this.onEnd,
  })  : assert(endTime != null || controller != null),
        super(key: key);

  @override
  _CountDownState createState() => _CountDownState();
}

class _CountDownState extends State<CountdownTimer> {
  late CountdownTimerController controller;

  CurrentRemainingTime? get currentRemainingTime =>
      controller.currentRemainingTime;

  Widget get endWidget => widget.endWidget;

  CountdownTimerWidgetBuilder get widgetBuilder =>
      widget.widgetBuilder ?? builderCountdownTimer;

  TextStyle? get textStyle => widget.textStyle;

  @override
  void initState() {
    super.initState();
    initController();
  }

  ///Generate countdown controller.
  initController() {
    controller = widget.controller ??
        CountdownTimerController(endTime: widget.endTime!, onEnd: widget.onEnd);
    if (controller.isRunning == false) {
      controller.start();
    }
    controller.addListener(() {
      if (mounted) {
        setState(() {});
      }
    });
  }

  @override
  void didUpdateWidget(CountdownTimer oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (oldWidget.endTime != widget.endTime || widget.controller != oldWidget.controller) {
      controller.dispose();
      initController();
    }
  }

  @override
  Widget build(BuildContext context) {
    return widgetBuilder(context, currentRemainingTime);
  }

  Widget builderCountdownTimer(
      BuildContext context, CurrentRemainingTime? time) {
    if (time == null) {
      return endWidget;
    }
    String value = '';
    if (time.days != null) {
      var days = _getNumberAddZero(time.days!);
      value = '$value$days days ';
    }
    var hours = _getNumberAddZero(time.hours ?? 0);
    value = '$value$hours : ';
    var min = _getNumberAddZero(time.min ?? 0);
    value = '$value$min : ';
    var sec = _getNumberAddZero(time.sec ?? 0);
    value = '$value$sec';
    return Text(
      value,
      style: textStyle,
    );
  }

  /// 1 -> 01
  String _getNumberAddZero(int number) {
    if (number < 10) {
      return "0" + number.toString();
    }
    return number.toString();
  }
}

Widget _defaultCountdownBuilder(BuildContext context, Duration currentRemainingTime) {
  return Text('${currentRemainingTime.inSeconds}');
}

typedef CountdownWidgetBuilder = Widget Function(BuildContext context, Duration time);

class Countdown extends StatefulWidget {
  ///controller
  final CountdownController countdownController;
  ///custom widget builder
  final CountdownWidgetBuilder builder;

  Countdown({
    required this.countdownController,
    this.builder = _defaultCountdownBuilder,
  });

  @override
  _CountdownState createState() => _CountdownState();
}

class _CountdownState extends State<Countdown> {
  CountdownWidgetBuilder get builder => widget.builder;

  CountdownController get countdownController => widget.countdownController;

  @override
  void initState() {
    super.initState();
    countdownController.addListener(() {
      setState(() {});
    });
  }

  Duration get time => countdownController.currentDuration;

  @override
  Widget build(BuildContext context) {
    return builder.call(context, time);
  }

  @override
  void dispose() {
    countdownController.dispose();
    super.dispose();
  }
}

class CountdownController extends ValueNotifier<int> {
  CountdownController({
    int? timestamp,
    Duration? duration,
    this.stepDuration = const Duration(milliseconds: 1000),
    this.onEnd,
  })  : assert((timestamp != null && timestamp > 0) || duration != null),
        super((timestamp ?? duration?.inMilliseconds)!);
  Timer? _diffTimer;
  int? _lastTimestamp;
  int? _lostTime;
  final Duration stepDuration;

  ///Event called after the countdown ends
  final VoidCallback? onEnd;

  bool get isRunning => _diffTimer != null;

  CurrentRemainingTime get currentRemainingTime {
    int? days, hours, min, sec;
    int _timestamp = (value / 1000).floor();
    if (value >= 86400) {
      days = (_timestamp / 86400).floor();
      _timestamp -= days * 86400;
    }
    if (_timestamp >= 3600) {
      hours = (_timestamp / 3600).floor();
      _timestamp -= hours * 3600;
    } else if(days != null) {
      hours = 0;
    }
    if (_timestamp >= 60) {
      min = (_timestamp / 60).floor();
      _timestamp -= min * 60;
    } else if(hours != null) {
      min = 0;
    }
    sec = _timestamp.toInt();
    return CurrentRemainingTime(days: days, hours: hours, min: min, sec: sec);
  }

  Duration get currentDuration {
    return Duration(milliseconds: value);
  }

  ///start
  start() {
    if (value <= 0) return;
    _dispose();
    Duration duration = _getDuration();
    if (duration == stepDuration) {
      _diffTimer = Timer.periodic(stepDuration, (Timer timer) {
        _diffTime(stepDuration);
      });
    } else {
      Future.delayed(duration, () {
        _diffTime(duration);
        _diffTimer = Timer.periodic(stepDuration, (Timer timer) {
          _diffTime(stepDuration);
        });
      });
    }
  }

  _diffTime(Duration duration) {
    value = max(value - duration.inMilliseconds, 0);
    _lastTimestamp = DateTime.now().millisecond;
    if (value <= 0) {
      stop();
      onEnd?.call();
      return;
    }
  }

  ///pause
  stop() {
    if (_lastTimestamp != null && value > 0) {
      _lostTime = DateTime.now().millisecond - _lastTimestamp!;
    }
    _dispose();
  }

  Duration _getDuration() {
    if (_lostTime != null && _lostTime! > 0 && _lostTime! < 1000) {
      return Duration(milliseconds: 1000 - _lostTime!);
    }
    return stepDuration;
  }

  @override
  void dispose() {
    _dispose();
    super.dispose();
  }

  _dispose() {
    _diffTimer?.cancel();
    _diffTimer = null;
  }
}

class CurrentRemainingTime {
  final int? days;
  final int? hours;
  final int? min;
  final int? sec;
  final Animation<double>? milliseconds;

  CurrentRemainingTime({this.days, this.hours, this.min, this.sec, this.milliseconds});

  @override
  String toString() {
    return 'CurrentRemainingTime{days: $days, hours: $hours, min: $min, sec: $sec, milliseconds: ${milliseconds?.value}';
  }
}
///Countdown timer controller.
class CountdownTimerController extends ChangeNotifier {
  CountdownTimerController(
      {required int endTime, this.onEnd, TickerProvider? vsync})
      : this._endTime = endTime {
    if(vsync != null) {
      this._animationController = AnimationController(vsync: vsync, duration: Duration(seconds: 1));
    }
  }

  ///Event called after the countdown ends
  final VoidCallback? onEnd;

  ///The end time of the countdown.
  int _endTime;

  ///Is the countdown running.
  bool _isRunning = false;

  ///Countdown remaining time.
  CurrentRemainingTime? _currentRemainingTime;

  ///Countdown timer.
  Timer? _countdownTimer;

  ///Intervals.
  Duration intervals = const Duration(seconds: 1);

  ///Seconds in a day
  int _daySecond = 60 * 60 * 24;

  ///Seconds in an hour
  int _hourSecond = 60 * 60;

  ///Seconds in a minute
  int _minuteSecond = 60;

  bool get isRunning => _isRunning;

  set endTime(int endTime) => _endTime = endTime;

  ///Get the current remaining time
  CurrentRemainingTime? get currentRemainingTime => _currentRemainingTime;

  AnimationController? _animationController;

  ///Start countdown
  start() {
    disposeTimer();
    _isRunning = true;
    _countdownPeriodicEvent();
    if (_isRunning) {
      _countdownTimer = Timer.periodic(intervals, (timer) {
        _countdownPeriodicEvent();
      });
    }
  }

  ///Check if the countdown is over and issue a notification.
  _countdownPeriodicEvent() {
    _currentRemainingTime = _calculateCurrentRemainingTime();
    _animationController?.reverse(from: 1);
    notifyListeners();
    if (_currentRemainingTime == null) {
      onEnd?.call();
      disposeTimer();
    }
  }

  ///Calculate current remaining time.
  CurrentRemainingTime? _calculateCurrentRemainingTime() {
    int remainingTimeStamp =
    ((_endTime - DateTime.now().millisecondsSinceEpoch) / 1000).floor();
    if (remainingTimeStamp <= 0) {
      return null;
    }
    int? days, hours, min, sec;

    ///Calculate the number of days remaining.
    if (remainingTimeStamp >= _daySecond) {
      days = (remainingTimeStamp / _daySecond).floor();
      remainingTimeStamp -= days * _daySecond;
    }

    ///Calculate remaining hours.
    if (remainingTimeStamp >= _hourSecond) {
      hours = (remainingTimeStamp / _hourSecond).floor();
      remainingTimeStamp -= hours * _hourSecond;
    } else if (days != null) {
      hours = 0;
    }

    ///Calculate remaining minutes.
    if (remainingTimeStamp >= _minuteSecond) {
      min = (remainingTimeStamp / _minuteSecond).floor();
      remainingTimeStamp -= min * _minuteSecond;
    } else if (hours != null) {
      min = 0;
    }

    ///Calculate remaining second.
    sec = remainingTimeStamp.toInt();
    return CurrentRemainingTime(days: days, hours: hours, min: min, sec: sec, milliseconds: _animationController?.view);
  }

  disposeTimer() {
    _isRunning = false;
    _countdownTimer?.cancel();
    _countdownTimer = null;
  }

  @override
  void dispose() {
    disposeTimer();
    _animationController?.dispose();
    super.dispose();
  }
}


import 'dart:async';

import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';
import 'package:nss/components/relative_scale.dart';

import '../utils/style_sheet.dart';
import 'mytext.dart';

class CounterWidget extends StatefulWidget {
  String dateTime;
  final ValueChanged<int>? onChanged;
  double space=0;
  bool expand=true;
  bool lableFullText=false;
  bool showLable=true;
  double lableSize=1;
  double valueSize=1;
  double height=1;
  Color lableColor=Colors.transparent;
  Color valueColor=Colors.transparent;
   CounterWidget({super.key,
     required this.dateTime,
     this.onChanged,
     this.space=3,
     this.lableSize=8,
     this.valueSize=9,
     this.height=1,
     this.lableColor=Colors.transparent,
     this.valueColor=Colors.transparent,
     this.expand=true,
     this.lableFullText=false,
     this.showLable=true,
   });

  @override
  State<CounterWidget> createState() => _CounterWidgetState();
}

class _CounterWidgetState extends State<CounterWidget> with RelativeScale {

  Timer? countdownTimer;
  Duration myDuration = Duration(seconds: 0);

  String days='';
  String hours='';
  String minutes='';
  String seconds='';
  bool ended=false;



  void startTimer() {
    countdownTimer = Timer.periodic(Duration(seconds: 1), (_) => setCountDown());
  }

  void stopTimer() {
    setState(() => countdownTimer!.cancel());
    _setTime();
  }

  void resetTimer() {
    stopTimer();
    setState(() => myDuration = Duration(days: 5));
    _initiateClock();
    _setTime();
  }

  void setCountDown() {
    final reduceSecondsBy = 1;
    setState(() {
      final seconds = myDuration.inSeconds - reduceSecondsBy;
      if (seconds < 0) {
        countdownTimer!.cancel();
      } else {
        myDuration = Duration(seconds: seconds);
      }
      _setTime();
    });
  }

  _setTime(){

    String strDigits(int n) => n.toString().padLeft(2, '0');
    days = strDigits(myDuration.inDays);
    hours = strDigits(myDuration.inHours.remainder(24));
    minutes = strDigits(myDuration.inMinutes.remainder(60));
    seconds = strDigits(myDuration.inSeconds.remainder(60));
    setState(() {
      widget.onChanged!(myDuration.inSeconds);
      if(myDuration.inSeconds<=0){
        ended=true;
      }else{
        ended=false;
      }
    });
  }

  _initiateClock(){
    DateTime serverDate = DateTime.parse(widget.dateTime);
    DateTime currentDate = DateTime.now();
    Duration diff = serverDate.difference(currentDate);

    myDuration = diff;
    _setTime();
    startTimer();
  }

  @override
  void initState() {
    super.initState();
    _initiateClock();
  }

  @override
  void dispose() {
    super.dispose();
    countdownTimer!.cancel();
  }


  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Container(

      child: (ended==false)?counterWidget():Mytext('EXPIRED', Colors.red,size: sy(widget.valueSize),bold: true,)
    );
  }
  counterWidget(){
    return widget.showLable==true?Row(
      mainAxisAlignment: (widget.expand==false)?MainAxisAlignment.start:MainAxisAlignment.spaceAround,
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        cardItem((widget.lableFullText==true)?'DAYS':'DAY',days),
        SizedBox(width: sy(widget.space),),
        cardItem((widget.lableFullText==true)?'HOURS':'HR',hours),
        SizedBox(width: sy(widget.space),),
        cardItem((widget.lableFullText==true)?'MINUTES':'MIN',minutes),
        SizedBox(width: sy(widget.space),),
        cardItem((widget.lableFullText==true)?'SECONDS':'SEC',seconds),

      ],
    ):Mytext('${(days=='00')?'':'$days:'}$hours:$minutes:$seconds', widget.valueColor,size: sy(widget.valueSize),);
  }

  cardItem(String lable, value) {
    return Column(
      children: [
        Mytext(value,(widget.valueColor==Colors.transparent)?fc_1:widget.valueColor,size: sy(widget.valueSize),bold: true,),
        SizedBox(height: sy(widget.height),),
        Mytext(lable,(widget.lableColor==Colors.transparent)?fc_1:widget.lableColor,size: sy(widget.lableSize),),
      ],
    );

  }
}

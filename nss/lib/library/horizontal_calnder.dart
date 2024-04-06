import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import 'package:nss/components/custom_date.dart';
import '../components/mytext.dart';
import '../components/relative_scale.dart';
import '../utils/style_sheet.dart';

class HorizontalCalandar extends StatefulWidget {
  int start=0;
  int end=0;
  final ValueChanged<DateTime>? onCompleted;
  late Color backgroundColor;
  late Color selectionBackgroundColor;
  late Color textColor;
  late Color selectionTextColor;
  var disabledDate;
  DateTime initialDate=DateTime.now();
  bool circle=false;

  HorizontalCalandar({Key? key,this.start=0,this.end=30, this.onCompleted, this.backgroundColor=Colors.transparent, this.selectionBackgroundColor=Colors.transparent, this.textColor=Colors.transparent , this.selectionTextColor=Colors.transparent,required this.initialDate, this.disabledDate, this.circle=false }) : super(key: key);

  @override
  State<HorizontalCalandar> createState() => _HorizontalCalandarState();
}

class _HorizontalCalandarState extends State<HorizontalCalandar> with RelativeScale{

  List<DateTime> calanderDate=[];
  DateTime selectedDate=DateTime.now();

  ScrollController _scrollLocation = ScrollController();
  late Color backgroundColor;
  late Color selectionBackgroundColor;
  late Color textColor;
  late Color selectionTextColor;
  List disabledDate=[];
  @override
  void initState() {
    super.initState();

    setState(() {

      if(widget.textColor==Colors.transparent){
        textColor=fc_2;
      }else{
        textColor=widget.textColor;
      }

      if(widget.backgroundColor==Colors.transparent){
        backgroundColor=fc_bg;
      }else{
        backgroundColor=widget.backgroundColor;
      }

      if(widget.selectionBackgroundColor==Colors.transparent){
        selectionBackgroundColor=ThemePrimary;
      }else{
        selectionBackgroundColor=widget.selectionBackgroundColor;
      }

      if(widget.selectionTextColor==Colors.transparent){
        selectionTextColor=fc_bg;
      }else{
        selectionTextColor=widget.selectionTextColor;
      }
      selectedDate=widget.initialDate;
      if(widget.disabledDate!=null){
        disabledDate=widget.disabledDate;
      }


    });


   // DateTime strt=DateTime.now();
    DateTime strt=DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day-widget.start );
    DateTime end=DateTime(DateTime.now().year, DateTime.now().month, DateTime.now().day+widget.end);
    for (int i = 0; i <= end.difference(strt).inDays; i++) {
      calanderDate.add(strt.add(Duration(days: i)));
    }


  }
  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    selectedDate=widget.initialDate;
    if(widget.disabledDate!=null){
      disabledDate=widget.disabledDate;
    }
    return  Container(
      width: Width(context),

      child:  SingleChildScrollView(
        controller: _scrollLocation,
        scrollDirection: Axis.horizontal,
        child: Row(
          children: [

            for(int i=0;i<calanderDate.length;i++)
              cardDates(i)
          ],
        ),
      ),
    );
  }


  cardDates(int i) {
    bool isSelect=false;
    bool isDisbled=false;
    if(CustomeDate.date(selectedDate.toString())==CustomeDate.date(calanderDate[i].toString())){
      isSelect=true;
      if (_scrollLocation.hasClients){


       try{
         Scrollable.ensureVisible(
             GlobalObjectKey('${i}').currentContext!,
             duration: Duration(seconds: 1),
             alignment: 0,
             curve: Curves.easeInOutCubic);
       }catch(e){}
      }

    }else{
      isSelect=false;
    }

    for(int k=0;k<disabledDate.length;k++){
      if(disabledDate[k].toString()==calanderDate[i].toString()){

        isDisbled=true;
        break;
      }
    }



    String date = DateFormat('d').format(calanderDate[i]);
    String month = DateFormat('MMM').format(calanderDate[i]);
    String day = DateFormat('EEE').format(calanderDate[i]);


    return GestureDetector(
      key: GlobalObjectKey('$i'),
      behavior: HitTestBehavior.translucent,
      child:(widget.circle==false)? Container(
       // key: (widget.circle==false)?GlobalObjectKey('$i'):null,
        padding: EdgeInsets.fromLTRB(sy(10), sy(5), sy(10), sy(5)),
        margin: EdgeInsets.fromLTRB(sy(4), sy(0), sy(5), sy(0)),
        decoration: decoration_round((isSelect==true)?selectionBackgroundColor:backgroundColor, sy(5), sy(5), sy(5), sy(5)),
        child: Column(
          children: [
            Mytext(month.toString().toUpperCase(),(isSelect==true)?selectionTextColor:(isDisbled==true)?textColor.withOpacity(0.3) :textColor,size: sy(s-1) ),
            SizedBox(height: sy(3),),
            Mytext(date.toString().toUpperCase(), (isSelect==true)?selectionTextColor: (isDisbled==true)?textColor.withOpacity(0.3) :textColor,size: sy(n),bold: true,),
            SizedBox(height: sy(2),),
            Mytext(day.toString().toUpperCase(), (isSelect==true)?selectionTextColor:(isDisbled==true)?textColor.withOpacity(0.3) : textColor,size: sy(s-1),),
          ],
        ),
      ):Container(
       //  key: (widget.circle==true)?GlobalObjectKey('$i'):null,
        width: sy(29),
        height: sy(29),
        alignment: Alignment.center,
        margin: EdgeInsets.fromLTRB(sy(4), sy(0), sy(5), sy(0)),
        decoration: decoration_round((isSelect==true)?selectionBackgroundColor:backgroundColor, sy(50), sy(50), sy(50), sy(50)),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [

            Mytext(day.toString().toUpperCase(), (isSelect==true)?selectionTextColor:(isDisbled==true)?textColor.withOpacity(0.3) : textColor,size: sy(s-2),bold: true,),
            SizedBox(height: sy(1),),
            Mytext(date.toString().toUpperCase()+' '+month.toString(), (isSelect==true)?selectionTextColor: (isDisbled==true)?textColor.withOpacity(0.3) :textColor,size: sy(xs),bold: true, ),
            SizedBox(height: sy(2),),
          ],
        ),
      ),
      onTap: (){
        setState(() {


          if(isDisbled==false){
            selectedDate=calanderDate[i];
            Scrollable.ensureVisible(
                GlobalObjectKey('${i}').currentContext!,
                duration: Duration(seconds: 1),
                alignment: 0,
                curve: Curves.easeInOutCubic);
            widget.onCompleted?.call(selectedDate);
          }


        });
      },
    );
  }


}



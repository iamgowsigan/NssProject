import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/components/widget_help.dart';
import 'package:shimmer/shimmer.dart';

import '../../components/countdown_timer.dart';
import '../../components/distance_calc.dart';
import '../utils/style_sheet.dart';

class Mytextbox extends StatefulWidget {

  String title;
  String hint;
  double paddingHorizontal=0;
  double paddingVertical=0;
  double height=0;
  String value;
  String arrkey;
  String arrvalue;
  List arr;
  IconData icondata;
  int maxline=100;

  Mytextbox(
      {Key? key,
        this.title='',
        this.hint='',
        required this.value,
        required this.arr,
        required this.arrkey,
        required this.arrvalue,
        this.paddingHorizontal=0,
        this.paddingVertical=0,
        this.height=0,
        this.maxline=100,
        this.icondata=Icons.circle_rounded,

      })
      : super(key: key);

  @override
  State<Mytextbox> createState() => _MytextboxState();
}

class _MytextboxState extends State<Mytextbox> with RelativeScale {


  @override
  void initState() {
    super.initState();
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Container(
      padding: EdgeInsets.fromLTRB(widget.paddingHorizontal, sy(0), widget.paddingHorizontal, widget.paddingVertical),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if(widget.title!='')Container(
            padding: EdgeInsets.fromLTRB(sy(0), sy(13), sy(0), sy(5)),
            child: Mytext(
              widget.title,fc_3,size: sy(s),bold: false,
            ),
          ),
          Container(
            width: Width(context),
            decoration: decoration_border(fc_textfield_bg, fc_textfield_bg, sy(1), sy(5), sy(5), sy(5), sy(5)),
            padding: EdgeInsets.fromLTRB(sy(7), sy(0), sy(7), sy(0)),
           // alignment: Alignment.centerLeft,
            height:(widget.height!=0)?(widget.height):null,
            child: Row(
              children: [
                Expanded(child: Mytext(widget.value==''?widget.hint:WidgetHelp.getNameFromId(widget.value, widget.arr, widget.arrkey, widget.arrvalue),widget.value==''?fc_5:fc_2,size: sy(n-1),textAlign: TextAlign.start,maxLines: widget.maxline,textOverflow: TextOverflow.ellipsis,)),
                if(widget.icondata!=Icons.circle_rounded)Icon(Icons.circle_rounded,size: sy(l),color: fc_2,)
              ],
            )
          ),



        ],
      ),
    );
  }

 }

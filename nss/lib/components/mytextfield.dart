import 'package:carousel_slider/carousel_slider.dart';
import 'package:flutter/material.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:shimmer/shimmer.dart';

import '../../components/countdown_timer.dart';
import '../../components/distance_calc.dart';

class Mytextfield extends StatefulWidget {

  String title;
  String hint;
  String value='';
  final ValueChanged<String>? onChanged;
  double paddingHorizontal=0;
  double paddingVertical=0;
  double height=0;
  TextCapitalization textCapitalization = TextCapitalization.sentences;
  TextInputAction textInputAction = TextInputAction.next;
  TextInputType textInputType = TextInputType.name;
  IconData suffixIcon=Icons.circle_rounded ;
  String suffixText='';
  bool clearButton=false;



  Mytextfield(
      {Key? key,
        this.title='',
        this.hint='',
        this.value='',
        this.paddingHorizontal=0,
        this.paddingVertical=0,
        this.height=0,
        this.onChanged,
        this.textCapitalization=TextCapitalization.sentences,
        this.textInputAction=TextInputAction.next,
        this.textInputType=TextInputType.name,
        this.suffixIcon=Icons.circle_rounded,
        this.suffixText='',
        this.clearButton=false,

      })
      : super(key: key);

  @override
  State<Mytextfield> createState() => _MytextfieldState();
}

class _MytextfieldState extends State<Mytextfield> with RelativeScale {

  TextEditingController ETtitle = TextEditingController();


  @override
  void initState() {
    super.initState();
    ETtitle.text=widget.value;

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
              widget.title,fc_3,size: sy(s),
            ),
          ),
          Container(
            decoration: decoration_border(fc_textfield_bg, fc_textfield_bg, sy(1), sy(5), sy(5), sy(5), sy(5)),
            padding: EdgeInsets.fromLTRB(sy(7), sy(0), sy(5), sy(0)),
            alignment: Alignment.center,
            height:(widget.height!=0)?(widget.height):null,
            child: TextField(
            //  controller: TextEditingController(text: widget.value),
              controller: (widget.value=='')?ETtitle:(ETtitle..text=widget.value.toString()..selection= TextSelection.collapsed(offset: widget.value.toString().length)),
              keyboardType: widget.textInputType,
              textCapitalization: widget.textCapitalization,
              textAlign: TextAlign.left,
              maxLines: (widget.textInputAction==TextInputAction.newline)?100:null,
              textInputAction: widget.textInputAction,
              onChanged: (val){
                widget.onChanged?.call(val);
              },
              decoration: InputDecoration(
                isDense: true,
                hintText: widget.hint,
                hintStyle: ts_Regular(sy(n-1), fc_5),
                focusedBorder: InputBorder.none,
                enabledBorder: InputBorder.none,
                border: InputBorder.none,
                suffix: (widget.suffixText!='')?Mytext(widget.suffixText, fc_2,size: sy(s),):null,
                suffixIcon: (widget.clearButton==false)?((widget.suffixIcon!=Icons.circle_rounded)?Icon(Icons.account_balance,color: fc_2,size: sy(n+1),):null): (ETtitle.text!='')?GestureDetector(
                  onTap: (){

                      ETtitle.clear();
                      widget.onChanged?.call('');

                  },
                  child: Icon(Icons.clear,size: sy(l),color: fc_2,),
                ):null,
              ),
              style: ts_Regular(sy(n-1), fc_1),
              autofocus: false,
              textAlignVertical: TextAlignVertical.center,
            ),
          ),



        ],
      ),
    );
  }

}


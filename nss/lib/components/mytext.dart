import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:nss/components/relative_scale.dart';

import '../utils/style_sheet.dart';
import '../utils/translation_widget.dart';

class Mytext extends StatelessWidget with RelativeScale {
  bool bold=false;
  bool block=false;
  bool translate=false;
  bool italic=false;
  bool strike=false;
  bool underline=false;
  double size=0;
  double padding=0;
  double height=0;
  Color? color=Colors.black;
  TextAlign textAlign=TextAlign.start;
  int maxLines=10000;
  String text;
  FontWeight fontWeight=FontWeight.w900;
  double letterspacing=0;
  //Icons
  IconData icon;
  bool enableIcon=false;
  double iconSize=0;
  double iconPadding=5;
  Color iconColor=Colors.black;
  MainAxisAlignment mainAxisAlignment=MainAxisAlignment.start;
  double topSpace=0;
  double bottomSpace=0;
  TextOverflow textOverflow=TextOverflow.clip;
  String customFont='';


  Mytext( String this.text,
      Color? this.color,
      {Key? key,
        this.bold=false,
        this.block=false,
        this.translate=false,
        this.italic=false,
        this.strike=false,
        this.underline=false,
        this.size=0,
        this.textAlign=TextAlign.start,
        this.maxLines=10000,
        this.fontWeight=FontWeight.w800,
        this.letterspacing=0,
        this.padding=0,
        this.height=0,
        this.icon=Icons.account_balance_outlined,
        this.enableIcon=false,
        this.iconSize=0,
        this.iconPadding=3,
        this.topSpace=0,
        this.bottomSpace=0,
        this.iconColor=Colors.transparent,
        this.textOverflow=TextOverflow.clip,
        this.mainAxisAlignment=MainAxisAlignment.start,
        this.customFont='',
      }) : super(key: key);


  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    //Regular
    TextStyle styleRegular=TextStyle(
        fontSize: (size!=0)?size:sy(n),
        color: color,
        fontWeight: FontWeight.w500,
        height: (height==0)?null:height,
        letterSpacing: letterspacing==0?null:letterspacing,
        fontStyle: italic==true?FontStyle.italic:null,
        decoration: (strike==true)?TextDecoration.lineThrough:(underline==true)?TextDecoration.underline:null
    );
    //Bold
    TextStyle styleBold=TextStyle(
        fontSize: (size!=0)?size:sy(n),
        color: color,
        fontWeight: (block==true)?FontWeight.w900:fontWeight,
        height:(height==0)?1.2:height,
        letterSpacing: letterspacing==0?null:letterspacing,
        fontStyle: italic==true?FontStyle.italic:null,
        decoration: (strike==true)?TextDecoration.lineThrough:(underline==true)?TextDecoration.underline:null
    );

    TextStyle customFontStyle=(customFont!='')?GoogleFonts.getFont(customFont,
      color: color,
      fontWeight: (bold==true)?FontWeight.w900:null,
      height:(height==0)?1.2:height,
      fontStyle: italic==true?FontStyle.italic:null,
      fontSize:  (size!=0)?size:sy(n),
        decoration: (strike==true)?TextDecoration.lineThrough:(underline==true)?TextDecoration.underline:null
    ):styleRegular;

    Widget textWidget = (translate==false)?
    Text( text.toString(),style: (customFont=='')?((bold==false)?styleRegular:styleBold):customFontStyle,textAlign: textAlign,maxLines: maxLines,softWrap: true,overflow: textOverflow,):
    TranslationWidget(message: text.toString(), style:  (bold==false)?styleRegular:styleBold,textAlign: textAlign,maxLines: maxLines,softWrap: true,overflow: textOverflow);

    return Container(

        padding: EdgeInsets.fromLTRB(sy(padding), topSpace, sy(padding), bottomSpace),
        child: (enableIcon==true)?Row(
          mainAxisAlignment: mainAxisAlignment,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [
            if(enableIcon==true)Container(
              child: Icon(icon,color: iconColor==Colors.transparent?color:iconColor,size: iconSize==0? sy(n):iconSize,),
            ),
            if(enableIcon==true)SizedBox(width: sy(iconPadding),),
            textWidget,
          ],
        ):textWidget
    );

  }


}

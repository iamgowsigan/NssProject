import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_cropper/image_cropper.dart';
import 'package:image_picker/image_picker.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/library/list_animation.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:nss/utils/const.dart';
import 'package:provider/provider.dart';
import 'dart:convert';
import 'package:path/path.dart' as path;
import 'package:http/http.dart' as http;

import '../utils/style_sheet.dart';

class PopWidget extends StatefulWidget{
  String title='';
  List<Widget> widget;
  PopWidget({Key? key,required this.title,required this.widget }) : super(key: key);

  @override
  State<PopWidget> createState() => _PopWidgetState();
}

class _PopWidgetState extends State<PopWidget>  with RelativeScale {


  String result='';
  late File finalImage;
  Map data = Map();


  @override
  void initState() {
    super.initState();


  }

  @override
  void dispose() {
    super.dispose();
  }


  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return MaterialApp(
        themeMode: Provider.of<AppSetting>(context).appTheam,
        builder: (context, child) {
          return Directionality(
            textDirection:
            Const.AppLanguage == 0 ? TextDirection.ltr : TextDirection.rtl,
            child: child!,
          );
        },
        //color: Colors.grey.withOpacity(0.8),
        home: SafeArea(
          child: GestureDetector(
            onTap: (){
              closeScreen();
            },
            child:screenBody(),
          ),
        )
    );
  }

  screenBody() {
    double itemSize=Width(context)*0.165;
    return Container(
        width: Width(context),
        height: Height(context),
        color: Colors.grey.withOpacity(0.3),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.start,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Spacer(),
            Material(
              elevation: 4,
              borderRadius: BorderRadius.only(topLeft: Radius.circular(sy(15)),topRight: Radius.circular(sy(15))),
              child:  Container(
                width: Width(context),
                decoration: decoration_round(fc_bg, sy(10), sy(10), 0, 0),
                //  padding: EdgeInsets.fromLTRB(sy(5), sy(10), sy(5), sy(5)),
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.start,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [

                    Container(
                      width: Width(context)*0.2,
                      height: sy(3),
                      margin: EdgeInsets.fromLTRB(sy(0) , sy(5), sy(0), sy(0)),
                      decoration: decoration_round(fc_4, sy(5), sy(5), sy(5), sy(5)),),

                    Container(
                      padding: EdgeInsets.fromLTRB(sy(5), sy(7), sy(10), sy(10)),
                      child: Row(
                        children: [
                          Expanded(child: Mytext(widget.title, fc_2,bold: true,size: sy(n),padding: sy(5),),),
                          GestureDetector(onTap: (){
                            closeScreen();
                          }, child: Mytext('Done',ThemePrimary,size: sy(n),)
                          ),
                          SizedBox(width: sy(5),),
                        ],
                      ),
                    ),

                    ListAnimation(
                        vertical: true,
                        horizontal: false,
                        children: widget.widget
                    ),


                    SizedBox(height: sy(10),),


                  ],
                ),
              ),
            ),
          ],
        )
    );
  }

  closeScreen(){
    Navigator.of(context).pop(
        {'result':result,
          'name':'answer2',});
  }



}

// Navigator.push(context, TransparantScreen(widget: PopWidget(title: 'Pop Up',widget: [
// Mytext('Hello', fc_2),
// Mytext('Hello', fc_2),
// Mytext('Hello', fc_2),
// ], ))).then((results) {
// setState(() {
//
// });
// });
import 'package:flutter/material.dart';
import 'package:nss/library/list_animation.dart';
import '../utils/app_settings.dart';
import '../utils/style_sheet.dart';
import 'mytext.dart';

CustomBottomSheet({required BuildContext context, required String title, required Function sy, required Widget child,double height=0.5,bool doneButton=false,Widget? bottomWidget,Widget? topWidget}) {

  return showModalBottomSheet(
      context: context,
      backgroundColor: fc_bg,
      isScrollControlled: true,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.only(topLeft: Radius.circular(sy(10.0)), topRight: Radius.circular(sy(10.0))),
      ),
      builder: (BuildContext bc) {
        return SafeArea(
          top: true,
            child:
        Container(
            width: Width(context),
            height: Height(context)*height,
           //  padding: EdgeInsets.fromLTRB(0,MediaQuery.of(context).padding.top,0,0),
            //color: fc_bg,
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                if(title!='')Container(
                  padding: EdgeInsets.fromLTRB(sy(15.0),sy(10.0),sy(15.0),sy(7.0)),
                  child: Row(
                    children: [
                      Expanded(child: Mytext(title, fc_1, textAlign: TextAlign.left,size: sy(n+1),),),
                      GestureDetector(
                        behavior: HitTestBehavior.translucent,
                        onTap: (){
                          Navigator.of(context).pop();
                        },
                        child:(doneButton==true)? Mytext('Done',ThemePrimary,size: sy(s),):Icon(Icons.clear,size: sy(ll),color: fc_2,),
                      )
                    ],
                  ),
                ),

                if(title=='') Container(
                  height: sy(3.0),
                  width: sy(50.0),
                  decoration: decoration_round(
                      Colors.grey.shade400, sy(20.0), sy(20.0), sy(20.0), sy(20.0)),
                  margin: EdgeInsets.fromLTRB(sy(5.0), sy(5.0), sy(5.0), sy(5.0)),
                ),
                if(topWidget!=null)Container(
                  width: Width(context),
                  child: topWidget,
                ),
                Expanded(child: Padding(
                  padding: EdgeInsets.only(bottom: MediaQuery.of(context).viewInsets.bottom),
                  child: Container(
                    padding: EdgeInsets.fromLTRB(sy(5.0),sy(2.0),sy(5.0),sy(2.0)),
                    child: SingleChildScrollView(
                      scrollDirection: Axis.vertical,
                      child:  ListAnimation(
                        vertical: true,
                        horizontal: false,
                        duration:500 ,
                        children: <Widget>[


                          child,


                          SizedBox(width: Width(context),height: sy(10.0),)
                        ],
                      ),
                    ),
                  ),
                )),
                if(bottomWidget!=null)Container(
                  width: Width(context),
                  child: bottomWidget,
                )
              ],
            )
        )
        );
      });
}
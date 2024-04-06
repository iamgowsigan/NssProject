import 'dart:io';
import 'package:flutter/material.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/style_sheet.dart';

class AppHeader extends StatefulWidget {
  Widget child;
  Color backgroundColor;
  bool topSafe;
  bool bottomSafe;
  BuildContext mcontext;
  AppHeader({Key? key,required this.mcontext, required this.child, this.backgroundColor=Colors.transparent,this.topSafe=true, this.bottomSafe=true}) : super(key: key);

  @override
  State<AppHeader> createState() => _AppHeaderState();
}

class _AppHeaderState extends State<AppHeader> {

  bool serviceInternet=false;

  bool ActiveConnection = false;
  String T = "";
  Future CheckUserConnection() async {
    try {
      final result = await InternetAddress.lookup('google.com');
      if (result.isNotEmpty && result[0].rawAddress.isNotEmpty) {
        setState(() {
          ActiveConnection = true;
          T = "Turn off the data and repress again";
        });
      }
    } on SocketException catch (_) {
      setState(() {
        ActiveConnection = false;
        T = "Turn On the data and repress again";
      });
    }
  }


  @override
  Widget build(mcontext) {
    return Directionality(
        textDirection: Const.AppLanguage == 0 ? TextDirection.ltr : TextDirection.rtl,
        child: Container(
          width: Width(context),
          height: Height(context),
          color: widget.backgroundColor==Colors.transparent?fc_bg:widget.backgroundColor,
          child: SafeArea(
            top: widget.topSafe,
            bottom: widget.bottomSafe,
            child: Stack(
              children: [
                Positioned(
                  child: widget.child,
                  top: 0,
                  bottom: 0,
                  left: 0,
                  right: 0,
                ),
                Positioned(
                    top: 0,
                    left: 0,
                    right: 0,
                    child: Material(
                      child: Container(
                        padding: EdgeInsets.fromLTRB(20, 20, 20, 20),
                        color: fc_1,
                        child: Row(
                          mainAxisAlignment: MainAxisAlignment.start,
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            Icon(Icons.language,size: 20,color: fc_bg,),
                            SizedBox(width: 15,),
                            Expanded(child: Column(
                              mainAxisAlignment: MainAxisAlignment.start,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [

                              Mytext('$serviceInternet Check your Internet connection', fc_bg,size: 15,),
                            ],)),
                            GestureDetector(
                              onTap: (){
                                setState(() {
                                  serviceInternet=!serviceInternet;

                                });
                              },
                              child: Container(
                                padding: EdgeInsets.fromLTRB(9, 5, 9, 5),
                                decoration: decoration_round(fc_bg, 8, 8, 8, 8),
                                child: Mytext('Retry',fc_2,size: 12,),
                              ),
                            )
                          ],
                        ),
                      ),
                    )),
              ],
            ),
          )
        )
    );
  }
}

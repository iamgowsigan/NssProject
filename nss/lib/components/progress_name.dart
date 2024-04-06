import 'package:flutter/material.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/urls.dart';
import 'package:shimmer/shimmer.dart';

import '../utils/style_sheet.dart';

class MyProgressName extends StatelessWidget {

  bool showProgress;
  MyProgressName(this.showProgress, {Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    // TODO: implement build
    return Visibility(
      visible: showProgress,
      child: Container(
        color: Colors.white,
        width: MediaQuery.of(context).size.width,
        height: MediaQuery.of(context).size.height,
       alignment: Alignment.center,
       child:Shimmer.fromColors(
         child: Column(
           mainAxisAlignment: MainAxisAlignment.center,
           children: <Widget>[
             Image.asset(
               Urls.DummyLogo,
               height: 80.0,
             ),
             SizedBox(height: 5,),

             Text(
               Const.AppName,
               style: ts_Bold(40, Colors.grey  ),
             ),
             SizedBox(height: 120,),
           ],
         ),
         baseColor: Colors.black12,
         highlightColor: Colors.white,
       ),

      ),
    );
  }
}

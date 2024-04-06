import 'package:flutter/cupertino.dart';
import 'package:flutter/material.dart';

import '../library/flashnotification.dart';
import '../library/pop_message.dart';
import '../utils/app_settings.dart';
import '../utils/style_sheet.dart';

class Pop{

  static void successPop(BuildContext context ,String title ,String message, IconData icon) {
    showFlash(
      context: context,
      transitionDuration: Duration(milliseconds: 700),
      duration: Duration(milliseconds: 7000),
      builder: (context, controller) {

        return Flash(
          controller: controller,
          behavior: FlashBehavior.floating,

          boxShadows: kElevationToShadow[4],
          backgroundColor: ThemePrimary,
          brightness: Brightness.light,
         // barrierBlur:50,
         // barrierColor:Colors.green,
          barrierDismissible:false,
          horizontalDismissDirection: HorizontalDismissDirection.endToStart,
          position: FlashPosition.top,
          child: FlashBar(
          //  padding: EdgeInsets.fromLTRB(10, 30, 30, 10),
            icon: Icon(icon,
              size: 30.0,
              color: Colors.white,
            ),
            title: Text(title,style: ts_Bold(12, Colors.white,),),
            content: Text(message,style: ts_Regular(10, Colors.white,),),
            indicatorColor: Colors.blue,
            showProgressIndicator: true,
            shouldIconPulse: true,
            actions: [

              TextButton(
                onPressed: () => controller.dismiss(),
                child: Text(Lang('Close'  , 'يغلق' ), style: ts_Bold(15,Colors.white)), )

            ],
          ),
        );;
      },
    );
  }
  static void errorPop(BuildContext context ,String title ,String message, IconData icon) {


    showFlash(
      context: context,
      transitionDuration: Duration(milliseconds: 700),
      duration: Duration(milliseconds: 7000),
      builder: (context, controller) {



        return Flash(
          controller: controller,
          behavior: FlashBehavior.floating,
          boxShadows: kElevationToShadow[4],
          backgroundColor: Colors.red[400],
          brightness: Brightness.light,
          //barrierBlur:50,
         // barrierColor:Colors.red,
          barrierDismissible:false,
          horizontalDismissDirection: HorizontalDismissDirection.endToStart,
          position: FlashPosition.top,
          child: FlashBar(
            icon: Icon(icon,
              size: 40.0,
              color: Colors.white,
            ),
            title: Text(title,style: ts_Bold(15, Colors.white,),),
            content: Text(message,style: ts_Regular(12, Colors.white,),),
            indicatorColor: Colors.blue,
            showProgressIndicator: true,
            shouldIconPulse: true,
            actions: [

              TextButton(
                onPressed: () => controller.dismiss(),
                child: Text(Lang('Close'  , 'يغلق' ), style: ts_Bold(15,Colors.white)), )

            ],
          ),
        );
      },
    );
  }

  static void messagePop(BuildContext context ,String title ,String message, IconData icon,List<Widget> widgetlist) {
    showFlash(
      context: context,
      transitionDuration: Duration(milliseconds: 700),
      duration: Duration(milliseconds: 4000),
      builder: (context, controller) {
        return Flash(
          controller: controller,
          boxShadows: kElevationToShadow[4],
          behavior: FlashBehavior.floating,
          backgroundColor: ThemePrimary,
          position: FlashPosition.top,
          child: FlashBar(
            icon: Icon(icon,
              size: 40.0,
              color: Colors.white,
            ),
            title: Text(title,style: ts_Bold(17, Colors.white,),),
            content: Text(message,style: ts_Regular(14, Colors.white,),),
            shouldIconPulse: true,
            actions: [

              TextButton(
                onPressed: () => controller.dismiss(),
                child: Text(Lang('Close'  , 'يغلق' ), style: ts_Bold(12,Colors.white)), ),

              widgetlist[0],

            ],
          ),
        );
      },
    );
  }


  static void successTop(BuildContext context ,String message) {
    AchievementView(
      context,
      title: message,
      icon: Icon(Icons.check_circle,size: 30,color: Colors.white,),
    //  subTitle: "Training completed successfully!",
      isCircle: false,
      color: Colors.green.shade400,
    ).show();

  }

  static void errorTop(BuildContext context ,String message) {
    AchievementView(
      context,
      title: message,
      icon: Icon(Icons.error_outline,size: 30,color: Colors.white,),
      //  subTitle: "Training completed successfully!",
      isCircle: false,
      color: Colors.red.shade400,
    ).show();

  }

  static void infoTop(BuildContext context ,String message) {
    AchievementView(
      context,
      title: message,
      icon: Icon(Icons.sentiment_neutral,size: 30,color: Colors.white,),
      //  subTitle: "Training completed successfully!",
      isCircle: false,
      color: Colors.blue.shade400,
    ).show();


  }

}
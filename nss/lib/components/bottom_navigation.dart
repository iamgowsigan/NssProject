import 'package:flutter/material.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/router/open_screen.dart';
import 'package:nss/screens/attendance_screen.dart';
import 'package:nss/screens/dashboard.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:nss/utils/const.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../screens/profile_screen.dart';
import 'mytext.dart';

class BottomNavigationWidget extends StatefulWidget {
  BuildContext mcontext;
  bool ishome;
  int order;
  final ScrollController controller;

  BottomNavigationWidget(
      {Key? key,
        required this.ishome,
        required this.mcontext,
        required this.controller,
        required this.order})
      : super(key: key);

  @override
  _BottomNavigationWidgetState createState() {
    return _BottomNavigationWidgetState();
  }
}

class _BottomNavigationWidgetState extends State<BottomNavigationWidget> with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  String UserId = '';
  bool showProgress = false;
  late BuildContext mcontext;
  bool ishome = false;
  int order = 0;
  String skipSignup = '';



  @override
  void initState() {
    mcontext = widget.mcontext;
    ishome = widget.ishome;
    order = widget.order;
    super.initState();
    getSharedStore();
  }

  @override
  void dispose() {
    super.dispose();
  }

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      // UserId = prefs.getString(Const.UID) ?? '';
      UserId = Provider.of<AppSetting>(context, listen: false).sid;

      skipSignup = Provider.of<AppSetting>(context, listen: false).skipSignup;
    });
  }

  @override
  Widget build(BuildContext context) {
    // TODO: implement build
    initRelativeScaler(context);
    UserId = Provider.of<AppSetting>(context, listen: false).sid;
    skipSignup = Provider.of<AppSetting>(context, listen: false).skipSignup;

    return IntrinsicHeight(
      child: Container(
        decoration: BoxDecoration(
          color: ThemePrimary,
        ),
        child: SafeArea(
            child: Container(
              padding: EdgeInsets.fromLTRB(sy(5), sy(2), sy(5), sy(2)),
              child:  Row(
                //children inside bottom appbar
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.spaceAround,
                children: <Widget>[

                  GestureDetector(

                      onTap: (){

                        if (ishome == true) {
                          Navigator.pushReplacement(mcontext, OpenScreen(widget: Dashboard()));
                          //Navigator.of(mcontext).pop();
                        } else {
                          if (order != 1){
                            Navigator.of(mcontext).pop();
                          }
                        }
                      },
                      child:bottomMenuItem('Home', order == 1 ? Icons.home_outlined : Icons.home_outlined, order == 1 ? ThemePrimary : fc_4, 1)
                  ),

                  GestureDetector(
                      onTap: (){
                        if (ishome == true) {
                          Navigator.push(mcontext, OpenScreen(widget: AttendanceScreen()));

                        } else {
                          if (order != 2){
                            Navigator.pushReplacement(mcontext, OpenScreen(widget: AttendanceScreen()));
                          }

                        }
                      },
                      child:bottomMenuItem('Attendance', order == 2 ? Icons.menu : Icons.menu, order == 2 ? ThemePrimary : fc_4, 2)
                  ),

                  GestureDetector(

                      onTap: (){
                        if (ishome == true) {
                         Navigator.push(mcontext, OpenScreen(widget: ProfileScreen()));
                        } else {
                          if (order != 3)
                            Navigator.pushReplacement(mcontext, OpenScreen(widget: ProfileScreen()));
                        }
                      },
                      child:bottomMenuItem('Account', order == 3 ? Icons.person_outline : Icons.person_outline, order == 3 ? ThemePrimary : fc_4, 3)
                  ),

                ],
              ),
            )),
      ),
    );

  }

  bottomMenuItem(String lable, IconData icon, Color color, int index) {
    return Container(
      //width: Width(mcontext),
      padding: EdgeInsets.fromLTRB(0, sy(3), 0, sy(3)),

      child: Container(
        padding: EdgeInsets.fromLTRB(sy(7), sy(6), sy(9), sy(6)),
        decoration: decoration_round((index==order)?ThemeSecondary:ThemePrimary, sy(30), sy(30), sy(30), sy(30)),
        child: Row(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.center,
          children: [

            Container(
              child: Container(
                width: sy(16),
                height: sy(15),
                child: Stack(
                  children: [
                    Positioned(child:  Icon(
                      icon,
                      size: (index == order) ? sy(ll):sy(ll),
                      color: (index == order) ? ThemePrimary:ThemeSecondary,
                    ),bottom: 0,left: 0,right: 0,top: 0,),
                    if(Const.cartSize!=0 && icon==Icons.shopping_bag_outlined)Positioned(child: Container(
                      width: sy(10),
                      height: sy(10),
                      alignment: Alignment.center,
                      decoration: decoration_round(Colors.red, sy(5), sy(5), sy(5), sy(5)),
                      child: Mytext(Const.cartSize.toString(),Colors.white,size: sy(xs),),
                    ),top: 0,right: 0,)

                  ],
                ),
              )
              //padding: EdgeInsets.all(sy(4)),

            ),
            if(index==order) SizedBox(
              width: sy(5),
            ),
            if(index==order)Mytext(lable,  ThemePrimary,size: sy(s),bold: false, )
          ],
        ),
      )
    );
  }
}


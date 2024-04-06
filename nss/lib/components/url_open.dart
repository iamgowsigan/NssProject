import 'dart:io';
import 'package:flutter/src/material/scaffold.dart';
import 'package:flutter/src/widgets/framework.dart';
import 'package:nss/components/snakebar.dart';
import 'package:nss/utils/const.dart';
import 'package:url_launcher/url_launcher.dart';

class UrlOpenUtils {

  static whatsapp(GlobalKey<ScaffoldState> scaffoldKey) async {
    String url() {
      if (Platform.isIOS) {
        return "whatsapp://wa.me/${Const.WHATSAPPNUMBER}/?text=";
      } else {
        return "whatsapp://send?phone=${Const.WHATSAPPNUMBER}&text=";
      }
    }
    await canLaunchUrl(Uri.parse(url()))
        ? launchUrl(Uri.parse(url()))
        : print('error');
  }


  static whatsapptoAdmin(GlobalKey<ScaffoldState> scaffoldKey,String msg) async {
    String url() {
      if (Platform.isIOS) {
        return "whatsapp://wa.me/${Const.WHATSAPPNUMBER}/?text=${Uri.encodeFull(msg)}";
      } else {
        return "whatsapp://send?phone=${Const.WHATSAPPNUMBER}&text=${Uri.encodeFull(msg)}";
      }
    }
    await canLaunchUrl(Uri.parse(url()))
        ? launchUrl(Uri.parse(url()))
        : print('error');
  }




  static whatsappShop(GlobalKey<ScaffoldState> scaffoldKey,String Phone) async {
    String url() {
      if (Platform.isIOS) {
        return "whatsapp://wa.me/${Phone}/?text=";
      } else {
        return "whatsapp://send?phone=${Phone}&text=";
      }
    }
    await canLaunchUrl(Uri.parse(url()))
        ? launchUrl(Uri.parse(url()))
        : print('error');
  }

  static email(GlobalKey<ScaffoldState> scaffoldKey) async {
    var url =
        'mailto:' + Const.SHAREEMAIL + '?subject=Hello&body=MyMail';
    await canLaunchUrl(Uri.parse(url))
        ? launchUrl(Uri.parse(url))
        : SnakeBarUtils.Error(scaffoldKey, "Email cannot send");
  }

  static openurl(GlobalKey<ScaffoldState> scaffoldKey, String geturl,{bool isPlaystore=false}) async {
    var url = '' + geturl;
    await canLaunchUrl(Uri.parse(url))
        ? launchUrl(Uri.parse(url),mode: isPlaystore==false?LaunchMode.platformDefault:LaunchMode.externalApplication)
        : SnakeBarUtils.Error(scaffoldKey, "Invalid Url");
  }

  static call(GlobalKey<ScaffoldState> scaffoldKey, String phone) async {
    String getNumber = "tel:$phone";



    await canLaunchUrl(Uri.parse(getNumber))
        ? launchUrl(Uri.parse(getNumber))
        : debugPrint("err$getNumber");
  }
}

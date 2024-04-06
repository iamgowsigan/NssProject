import 'package:flutter/material.dart';
import 'package:provider/provider.dart';

import '../utils/app_settings.dart';
import '../utils/const.dart';
import '../utils/shared_preferences.dart';

SaveUserData( BuildContext context,List userDetail) {
  if (userDetail.length != 0) {
    SharedStoreUtils.setValue(Const.SID, userDetail[0]["s_id"]);
    SharedStoreUtils.setValue(Const.NAME, userDetail[0]["s_name"] ?? '');
    SharedStoreUtils.setValue(Const.LASTNAME, userDetail[0]["lastname"] ?? '');
    SharedStoreUtils.setValue(Const.PHONE, userDetail[0]["s_phone"]);
    SharedStoreUtils.setValue(Const.PROFILE, userDetail[0]["profile_pic"] ?? '');
    SharedStoreUtils.setValue(Const.COUNTRY, userDetail[0]["country"] ?? '');
    SharedStoreUtils.setValue(Const.CITY, userDetail[0]["city"] ?? '35');
    SharedStoreUtils.setValue(Const.EMAIL, userDetail[0]["s_email"] ?? '');
    SharedStoreUtils.setValue(Const.EMAIL_VERIFY, userDetail[0]["email_verify"] ?? '0');
    SharedStoreUtils.setValue(Const.GENDER, userDetail[0]["s_gender"] ?? '');
    SharedStoreUtils.setValue(Const.DOB, userDetail[0]["dob"] ?? '');
    SharedStoreUtils.setValue(Const.ABOUT, userDetail[0]["about"] ?? '');
    SharedStoreUtils.setValue(Const.EXPERT, userDetail[0]["s_domain"] ?? '');
    SharedStoreUtils.setValue(Const.REGNO, userDetail[0]["reg_no"] ?? '');




    Provider.of<AppSetting>(context, listen: false).sid = userDetail[0]["s_id"] ?? '0';
    Provider.of<AppSetting>(context, listen: false).name = userDetail[0]["s_name"] ?? '';
    Provider.of<AppSetting>(context, listen: false).lastname = userDetail[0]["lastname"] ?? '';
    Provider.of<AppSetting>(context, listen: false).phone = userDetail[0]["s_phone"] ?? '';
    Provider.of<AppSetting>(context, listen: false).profile = userDetail[0]["profile_pic"] ?? '';
    Provider.of<AppSetting>(context, listen: false).email = userDetail[0]["s_email"] ?? '';
    Provider.of<AppSetting>(context, listen: false).emailVerify = userDetail[0]["email_verify"] ?? '0';
    Provider.of<AppSetting>(context, listen: false).phone = userDetail[0]["s_phone"] ?? '';
    Provider.of<AppSetting>(context, listen: false).city = userDetail[0]["city"] ?? '35';
    Provider.of<AppSetting>(context, listen: false).country = userDetail[0]["country"] ?? '';
    Provider.of<AppSetting>(context, listen: false).about = userDetail[0]["about"] ?? '';
    Provider.of<AppSetting>(context, listen: false).gender = userDetail[0]["s_gender"] ?? '';
    Provider.of<AppSetting>(context, listen: false).expert = userDetail[0]["s_domain"] ?? '';
    Provider.of<AppSetting>(context, listen: false).dob = userDetail[0]["dob"] ?? '';
    Provider.of<AppSetting>(context, listen: false).reg_no = userDetail[0]["reg_no"] ?? '';


  }

}
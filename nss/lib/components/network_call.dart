import 'dart:convert';
import 'dart:io';
import 'package:http/http.dart' as http;

import '../utils/app_settings.dart';
import '../utils/const.dart';
import '../utils/urls.dart';

Map data = Map();
Future<Map> NetworkCall({var setState,var bodytag}) async{

  var body = bodytag;
  final response = await http.post(Uri.parse(Urls.validation),
      headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);

  data = json.decode(response.body);
  apiTest(data["success"].toString());


  return data;
}
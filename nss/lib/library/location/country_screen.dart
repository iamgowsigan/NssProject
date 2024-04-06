import 'dart:async';
import 'dart:typed_data';

import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:nss/components/mytext.dart';
import 'package:provider/provider.dart';
import 'package:location/location.dart' as loc;
import 'dart:ui' as ui;

import '../../components/relative_scale.dart';
import '../../utils/app_settings.dart';
import '../../utils/const.dart';
import '../../utils/style_sheet.dart';
import '../country.dart';

class CountryScreen extends StatefulWidget {
  CountryScreen({Key? key }) : super(key: key);

  @override
  _CountryScreenState createState() => _CountryScreenState();
}

class _CountryScreenState extends State<CountryScreen> with RelativeScale{

  TextEditingController ETsearch = TextEditingController();
  List countryList = World.Countries;
  List filterCountryList = [];
  String keyy = 'def';

  String countryName = World.Countries[100]['name'];
  String countryNameArab = World.Countries[100]['name'];
  String countryCodeint = World.Countries[100]['phonecode'].toString();
  String countryFlag = World.Countries[100]['sortname'].toString().toLowerCase() + '.png';

  @override
  void initState() {
    super.initState();
    filterCountryList = countryList;

   }


  @override
  void dispose() {
    super.dispose();
  }




  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Directionality(
      textDirection: Const.AppLanguage == 0 ? TextDirection.ltr : TextDirection.rtl,
      child: Container(
          child: SafeArea(
            top: false,
            child: Scaffold(
              body:Container(
                height: Height(context),
                width: Width(context),
                padding: EdgeInsets.fromLTRB(sy(7), MediaQuery.of(context).padding.top+sy(10), sy(7), sy(0)),
                color: fc_bg,
                child: Column(
                  children: [


                    Container(
                      alignment: Alignment.center,
                      decoration: decoration_border(fc_textfield_bg, fc_textfield_bg, sy(1), sy(5), sy(5), sy(5), sy(5)),
                     // height: sy(30),
                      padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(5), sy(0)),
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.center,
                        crossAxisAlignment: CrossAxisAlignment.center,
                        children: [
                          Expanded(
                            child: Container(
                           //   height: sy(),
                              padding: EdgeInsets.fromLTRB(sy(0), sy(2), sy(3), sy(2)),
                              alignment: Alignment.center,
                              child: TextField(
                                controller: ETsearch,
                                keyboardType: TextInputType.text,
                                textAlign: TextAlign.left,
                                decoration: InputDecoration(
                                  // counter: Offstage(),
                                    hintText: 'Search Country',
                                    hintStyle:
                                    ts_Regular(sy(n-1), fc_4),
                                    focusedBorder: InputBorder.none,
                                    enabledBorder: InputBorder.none,
                                    border: InputBorder.none,
                                    isDense: true
                                ),
                                style: ts_Regular(sy(n-1), fc_2),
                                textInputAction: TextInputAction.done,
                                autofocus: false,
                                onChanged: (val) {
                                  setState(() {
                                    List tempArr = [];
                                    for (int i = 0;
                                    i < countryList.length;
                                    i++) {
                                      if (countryList[i]['name'].toString().toLowerCase().contains(val.toLowerCase()) || countryList[i]['phonecode'].toString().toLowerCase().contains(val.toLowerCase()) ) {
                                        tempArr.add(countryList[i]);
                                      }
                                    }
                                    filterCountryList = tempArr;

                                    keyy = val;
                                  });
                                },
                              ),
                            ),
                          ),
                          GestureDetector(
                            behavior: HitTestBehavior.translucent,
                            onTap: () {
                              Navigator.of(context).pop(true);
                            },
                            child: Icon(
                              Icons.clear,
                              size: sy(xl),
                              color: fc_2,
                            ),
                          )
                        ],
                      ),
                    ),
                    SizedBox(height: sy(5),),

                    Expanded(child: Container(
                      child: SingleChildScrollView(
                        child: Container(
                          width: Width(context),
                          child: Wrap(
                            children: <Widget>[
                              if(ETsearch.text=='')Container(
                                width: Width(context),
                                padding: EdgeInsets.fromLTRB(sy(5), sy(10), sy(5), sy(15)),
                                child: Text('Frequently Countries',style: ts_Bold(sy(s), fc_4),),
                              ),
                              if(ETsearch.text=='') Container(
                                child: Wrap(
                                  children: [
                                    cardCountry(228),
                                    cardCountry(116),
                                    cardCountry(242),
                                    cardCountry(16),
                                    cardCountry(117),
                                    Container(
                                      width: Width(context),
                                      padding: EdgeInsets.fromLTRB(sy(5), sy(10), sy(5), sy(15)),
                                      child: Text('Other Countries',style: ts_Bold(sy(s), fc_4),),
                                    ),
                                  ],
                                ),
                              ),

                              for (int i = 0; i < filterCountryList.length; i++)
                                cardCountry(i),
                              SizedBox(
                                height: sy(30),
                              ),
                            ],
                          ),
                        ),
                      ),
                    )) ,

                  ],
                ),
              ),
            ),
          )
      ),
    );
  }

  String abc='';
  cardCountry(int i){


    return GestureDetector(

      child: Container(
        width: Width(context),
        padding: EdgeInsets.fromLTRB(sy(3), sy(1), sy(3), sy(1)),
        child: Column(
          children: [

            Row(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                ClipRRect(
                  //  borderRadius: BorderRadius.circular(sy(50)),
                  child: Image.asset(
                    "assets/images/flag/" + filterCountryList[i]['sortname'].toString().toLowerCase() + '.png',
                    width: sy(16),
                    //height: sy(20),
                    errorBuilder: (context,
                        exception,
                        stackTrack) =>
                        Icon(
                          Icons.flag,
                        ),
                    fit: BoxFit.contain,
                  ),
                ),
                SizedBox(
                  width: sy(5),
                ),
                Container(
                  width: sy(27),
                  child: Mytext(filterCountryList[i]['phonecode'].toString(),fc_1,bold: true,size: sy(s),
                  ),
                ),
                SizedBox(width: sy(5),),
                Expanded(child:  Mytext(Lang(filterCountryList[i]['name'], filterCountryList[i]['name']),fc_3),),

                Mytext(filterCountryList[i]['sortname'].toString(),fc_4,bold: true,size: sy(s),),
              ],
            ),
            Container(
              width: Width(context),
              color: fc_textfield_bg,
              height: sy(1),
              margin: EdgeInsets.fromLTRB(sy(0), sy(5), sy(0), sy(4)),
            )
          ],
        )

      ),
      behavior: HitTestBehavior.translucent,
      onTap: () {
        _setCountry(filterCountryList[i]['phonecode'],
            filterCountryList[i]['name'],
            filterCountryList[i]['name'],
            filterCountryList[i]['sortname'].toString().toLowerCase() + '.png');
      },
    );
  }
  _setCountry(String code, String eng, String ara, String img) {
    setState(() {
      countryName = eng;
      countryCodeint = code;
      countryNameArab = ara;
      countryFlag = img;
      contry_code = code;
      Navigator.of(context).pop(
          {'id':code.toString(),
            'name':countryName.toString(),
            'name_arab':countryName.toString(),
            'flag':countryFlag.toString()});
    });
  }


}

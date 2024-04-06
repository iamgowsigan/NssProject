import 'dart:async';
import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:geolocator/geolocator.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:provider/provider.dart';
import 'package:location/location.dart' as loc;
import 'dart:ui' as ui;
import 'package:geocoding/geocoding.dart';
import '../../components/mytext.dart';
import '../../components/relative_scale.dart';
import '../../components/widget_help.dart';
import '../../utils/const.dart';
import '../../utils/style_sheet.dart';
import '../country.dart';

class StateScreen extends StatefulWidget {
  String countrycode='';
  StateScreen({Key? key ,required this.countrycode}) : super(key: key);

  @override
  _StateScreenState createState() => _StateScreenState();
}

class _StateScreenState extends State<StateScreen> with RelativeScale{

  TextEditingController ETsearch = TextEditingController();
  List cityList = [];
  List filterCity=[];

  String
      stateName = '',
      stateNameArab = '',
      stateId = '';

  @override
  void initState() {
    super.initState();
    setState(() {
      for (int i = 0; i < World.States.length; i++) {
        if (World.States[i]['phonecode'].toString() == widget.countrycode || widget.countrycode=='') {
          cityList.add(World.States[i]);
        }
      }

      filterCity = cityList;

    });

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
                padding: EdgeInsets.fromLTRB(sy(10), MediaQuery.of(context).padding.top+sy(10), sy(10), sy(10)),
                color: fc_bg,
                child: Column(
                  children: [

                    Container(
                      padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(10)),
                      child: Row(
                        children: [

                          Expanded(child: Mytext( WidgetHelp.getNameFromId(widget.countrycode, World.Countries, 'phonecode', 'name'),fc_1,size: sy(s+1),bold: true,
                          ),),

                          Image.asset(
                            'assets/images/flag/' + WidgetHelp.getNameFromId( widget.countrycode, World.Countries,'phonecode' , 'sortname').toString().toLowerCase()+'.png'.toString(),
                            width: sy(12),
                            height: sy(12),
                            fit: BoxFit.contain,
                          ),
                        ],
                      ),
                    ),

                    Container(
                      decoration: decoration_round(fc_textfield_bg,  sy(5), sy(5), sy(5), sy(5)),
                     // height: sy(30),
                        padding: EdgeInsets.fromLTRB(sy(0), sy(2), sy(0), sy(2)),
                      child: Row(
                        children: [
                          Expanded(
                            child: Container(
                                padding: EdgeInsets.fromLTRB(sy(8), sy(0), sy(5), sy(0)),
                                alignment: Alignment.centerLeft,
                                child: TextField(
                                  controller: ETsearch,
                                  keyboardType: TextInputType.name,
                                  textCapitalization: TextCapitalization.sentences,
                                  textAlign: TextAlign.left,
                                  decoration: InputDecoration(
                                    // counter: Offstage(),
                                      hintText: 'Search city',
                                      hintStyle:
                                      ts_Regular(sy(n-1), fc_4),
                                      focusedBorder: InputBorder.none,
                                      enabledBorder: InputBorder.none,
                                      border: InputBorder.none,
                                      isDense: true),
                                  style: ts_Regular(sy(n-1), fc_2),
                                  textInputAction: TextInputAction.done,
                                  autofocus: false,
                                  onChanged: (val) {
                                    setState(() {
                                      List tempArr = [];
                                      for (int i = 0;
                                      i < cityList.length;
                                      i++) {
                                        if (cityList[i]['name']
                                            .toString()
                                            .toLowerCase()
                                            .contains(
                                            val.toLowerCase())) {
                                          tempArr.add(cityList[i]);
                                        }
                                      }
                                      filterCity = tempArr;
                                    });
                                  },
                                )),
                          ),
                          GestureDetector(
                            behavior: HitTestBehavior.translucent,
                            onTap: () {
                              Navigator.of(context).pop(true);
                            },
                            child: Icon(
                              Icons.clear,
                              size: sy(xl-1),
                              color: fc_2,
                            ),
                          ),
                          SizedBox(width: sy(5),)
                        ],
                      ),
                    ),
                    Container(
                      width: Width(context),
                      padding: EdgeInsets.fromLTRB(sy(5), sy(15), sy(5), sy(15)),
                      child: Text('Popular Cities',style: ts_Bold(sy(s), fc_4),),
                    ),

                    Expanded(child: Container(
                      child: SingleChildScrollView(
                        child: Container(
                          width: Width(context),
                          child: Wrap(
                            children: <Widget>[

                              for (int i = 0; i < filterCity.length; i++)
                                GestureDetector(
                                  child: Container(
                                    width: Width(context),
                                    padding: EdgeInsets.fromLTRB(sy(7), sy(3), sy(0), sy(2)),
                                    child: Column(
                                      mainAxisAlignment: MainAxisAlignment.start,
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [

                                        Row(
                                          children: [
                                            Icon(Icons.flag_circle,size: sy(n),color: ThemePrimary,),
                                            SizedBox(width: sy(5),),
                                            Expanded(child: Mytext(filterCity[i]['name'],fc_3,size: sy(n-1),),)
                                          ],
                                        ),
                                        Divider(color: fc_stock,),
                                      ],
                                    ),

                                  ),
                                  behavior: HitTestBehavior.translucent,
                                  onTap: () {
                                    _setCity(
                                        filterCity[i]['name'],
                                        filterCity[i]['name'],
                                        filterCity[i]['id']);
                                  },
                                ),
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

  _setCity(String eng, String ara, String id) {
    setState(() {
      stateName = eng;
      stateNameArab = ara;
      stateId = id;
      Navigator.of(context).pop(
          {'id':stateId.toString(),
            'name':stateName.toString(),
            'name_arab':stateNameArab.toString()});
    });
  }

}

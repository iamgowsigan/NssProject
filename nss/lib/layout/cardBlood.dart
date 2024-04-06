import 'package:flutter/material.dart';
import 'package:html_viewer_elite/html_viewer_elite.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/screens/blood_donor_screen.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:nss/utils/urls.dart';
import '../components/custom_image_viewer.dart';
import '../router/open_screen.dart';
import 'package:nss/router/open_screen.dart';
import 'package:nss/screens/blood_donor_screen.dart';

class cardBlood extends StatefulWidget {
  var arrayList;
  int i;
  GestureTapCallback favBtn;
  cardBlood({Key? key, required this.arrayList, required this.favBtn, required this.i}):super(key: key);

  @override
  State<cardBlood> createState() => _cardBloodState();
}

class _cardBloodState extends State<cardBlood> with RelativeScale{
  var arrItem;
  int i=0;

  @override
  void initState() {
    super.initState();
    arrItem = widget.arrayList;
  }
  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Container(
      width: MediaQuery.of(context).size.width,
      padding: EdgeInsets.fromLTRB(sy(10), sy(5), sy(10), sy(0)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            child: Row(
              children: [
                Container(
                  width: Width(context)*0.25,
                  height: Width(context)*0.25,
                  child: ClipRRect(
                    borderRadius: BorderRadius.circular(sy(5)),
                    child: CustomeImageView(
                      width: Width(context),
                      height: Width(context),
                      image:Urls.imageLocation+arrItem['b_image'].toString(),
                      placeholder: Urls.DummyImageBanner,
                      fit: BoxFit.contain,
                      blurBackground: false,
                    ),
                  ),
                ),
                SizedBox(width: sy(10),),
                Expanded(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    mainAxisAlignment: MainAxisAlignment.start,
                    children: [
                      Text(
                        arrItem['b_name'].toString().toUpperCase(),
                        style: TextStyle(
                          color: fc_1,
                          fontSize: sy(n+1),
                          fontWeight: FontWeight.w600,
                        ),
                      ),
                      SizedBox(
                        height: sy(3),
                      ),
                      Row(
                        children: [
                          Icon(Icons.call,color:Colors.red,size: sy(n+1),),
                          SizedBox(
                            width: sy(2),
                          ),
                          Text(
                            arrItem['b_phone'].toString(),
                            style: TextStyle(
                              color: fc_1,
                              fontSize: sy(n-1),
                              fontWeight: FontWeight.w400,
                            ),
                          ),
                        ],
                      ),
                      SizedBox(
                        height: sy(3),
                      ),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.start,
                        children: [
                          Row(
                            children: [
                              Icon(Icons.bloodtype,color:Colors.red,size: sy(n+1),),
                              SizedBox(
                                width: sy(2),
                              ),
                              Text(
                                arrItem['b_grp'].toString(),
                                style: TextStyle(
                                  color: fc_2,
                                  fontSize: sy(n-1),
                                  fontWeight: FontWeight.w400,
                                ),
                              ),
                            ],
                          ),
                          SizedBox(
                            width: sy(15),
                          ),
                          Row(
                            children: [
                              Icon(Icons.edit_calendar_sharp,color:Colors.red,size: sy(n+1),),
                              SizedBox(
                                width: sy(2),
                              ),
                              Text(
                                arrItem['b_age'].toString()+' Age',
                                style: TextStyle(
                                  color: fc_2,
                                  fontSize: sy(n-1),
                                  fontWeight: FontWeight.w400,
                                ),
                              ),
                            ],
                          ),
                        ],
                      ),
                      SizedBox(
                        height: sy(3),
                      ),


                    ],
                  ),
                ),
              ],
            ),
          ),
          Container(
            padding: EdgeInsets.fromLTRB(sy(0), sy(7), sy(4), sy(7)),
            child: Row(
              children: [
                (arrItem['b_status']=='0') ? Container(
                  decoration: decoration_border(fc_bg, fc_3, 0.9, sy(3), sy(3), sy(3), sy(3)),
                  padding: EdgeInsets.fromLTRB(sy(4), sy(2), sy(4), sy(2)),
                  child: Row(
                    children: [
                      Text(
                        'Not Donated',
                        style: TextStyle(
                          color: fc_3,
                          fontSize: sy(s),
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                      SizedBox(
                        width: sy(3),
                      ),
                      Icon(Icons.verified_outlined,color:fc_3,size: sy(n+1),),
                    ],
                  ),
                ) :
                Container(
                  decoration: decoration_border(fc_bg, ThemePrimary, 0.9, sy(3), sy(3), sy(3), sy(3)),
                  padding: EdgeInsets.fromLTRB(sy(4), sy(2), sy(4), sy(2)),
                  child: Row(
                    children: [
                      Text(
                        'Donated',
                        style: TextStyle(
                          color: fc_1,
                          fontSize: sy(s),
                          fontWeight: FontWeight.w500,
                        ),
                      ),
                      SizedBox(
                        width: sy(3),
                      ),
                      Icon(Icons.verified,color: ThemePrimary,size: sy(n+1),),
                    ],
                  ),
                ),

                Spacer(),
                ElevatedButton(
                  child: Text('See more',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: sy(s),
                    fontWeight: FontWeight.w600,
                  ),),
                  onPressed: () {
                    apiTest(arrItem.toString());
                    Navigator.push(context, OpenScreen(widget: BloodDonorScreen(arrayList: arrItem)));
                  },
                  style: ElevatedButton.styleFrom(
                    backgroundColor: ThemePrimary,
                    elevation: 0,
                    padding: EdgeInsets.fromLTRB(sy(5), sy(4), sy(5), sy(4)),
                    foregroundColor: ThemePrimary,
                    tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                    minimumSize: Size.zero,
                    shape: RoundedRectangleBorder(
                        borderRadius: BorderRadius.circular(sy(3))
                    ),
                    surfaceTintColor: fc_bg,
                  ),
                ),

              ],
            ),
          ),
          Divider(
            height: sy(1),
            thickness: 0.7,
            color: fc_4,
          ),
        ],
      ),
    );
  }
}

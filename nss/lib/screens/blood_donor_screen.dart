import 'package:flutter/material.dart';
import 'package:nss/components/bottom_navigation.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:shared_preferences/shared_preferences.dart';

import '../components/custom_image_viewer.dart';
import '../utils/app_settings.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';

class BloodDonorScreen extends StatefulWidget {
  var arrayList;
  BloodDonorScreen({Key? key, required this.arrayList }):super(key: key);

  @override
  State<BloodDonorScreen> createState() => _BloodDonorScreenState();
}

class _BloodDonorScreenState extends State<BloodDonorScreen>
    with RelativeScale {
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  ScrollController _scrollController = ScrollController();
  var donorItem;

  @override
  void initState() {
    super.initState();
    donorItem = widget.arrayList;
  }

  @override
  void dispose() {
    super.dispose();
  }

  // getSharedStore() async {
  //   SharedPreferences prefs = await SharedPreferences.getInstance();
  //   setState(() {
  //     userId = prefs.getString(Const.SID) ?? '';
  //     userId = Provider.of<AppSetting>(context, listen: false).sid;
  //     expert =  Provider.of<AppSetting>(context, listen: false).expert;
  //     phone =  Provider.of<AppSetting>(context, listen: false).phone;
  //
  //     // apiTest("SID :"+userId.toString());
  //     // apiTest("EXPERT :"+expert.toString());
  //     // apiTest("Phone :"+phone.toString());
  //
  //     name = Provider.of<AppSetting>(context, listen: false).name;
  //     //welcomeScreen = prefs.getString(Const.WELCOMESCREEN) ?? '0';
  //     _getServer();
  //
  //   });
  // }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return MaterialApp(
      home: SafeArea(
        top: false,
        child: Scaffold(
          key: _scaffoldKey,
          extendBody: true,
          // bottomNavigationBar: BottomNavigationWidget(
          //   ishome: false,
          //   mcontext: context,
          //   controller: _scrollController,
          //   order: 1,
          // ),
          appBar: AppBar(
            backgroundColor: fc_bg,
            titleSpacing: 0,
            elevation: 0,
            centerTitle: true,
            automaticallyImplyLeading: false,
            leading: IconButton(
              onPressed: () {
                Navigator.of(context).pop(true);
              },
              icon: Icon(
                Icons.arrow_back_ios,
                size: sy(l),
                color: fc_2,
              ),
            ),
            title: Container(
              child: Text(
                'Blood Requirements'.toUpperCase(),
                style: TextStyle(
                  fontSize: sy(l),
                  fontWeight: FontWeight.bold,
                  color: fc_2,
                ),
              ),
            ),
          ),
          body: Container(
            child: Stack(
              children: [
                Positioned(
                  child: _screenBody(),
                  top: 0,
                  bottom: 0,
                  left: 0,
                  right: 0,
                ),
                // Positioned(
                //   bottom: sy(20),
                //   left: sy(10),
                //   right: sy(10),
                //   child: Container(
                //     width: MediaQuery.of(context).size.width,
                //     height: 45,
                //     child: (donorItem['b_status']== '0')?ElevatedButton(
                //       child: Text('Mark as Donated',
                //         style: TextStyle(
                //           color: Colors.white,
                //           fontSize: sy(n),
                //           fontWeight: FontWeight.w600,
                //         ),),
                //       onPressed: () {
                //         apiTest(donorItem.toString());
                //         //apiTest(arrItem.toString());
                //         //Navigator.push(context, OpenScreen(widget: BloodDonorScreen(arrayList: arrItem)));
                //       },
                //       style: ElevatedButton.styleFrom(
                //         backgroundColor: ThemePrimary,
                //         elevation: 0,
                //         padding: EdgeInsets.fromLTRB(sy(5), sy(4), sy(5), sy(4)),
                //         foregroundColor: ThemePrimary,
                //         tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                //         minimumSize: Size.zero,
                //         shape: RoundedRectangleBorder(
                //             borderRadius: BorderRadius.circular(sy(3))
                //         ),
                //         surfaceTintColor: fc_bg,
                //       ),
                //     ): ElevatedButton(
                //       child: Text('Donated already',
                //         style: TextStyle(
                //           color: Colors.white,
                //           fontSize: sy(n),
                //           fontWeight: FontWeight.w600,
                //         ),),
                //       onPressed: () {
                //         apiTest(donorItem.toString());
                //         //Navigator.push(context, OpenScreen(widget: BloodDonorScreen(arrayList: arrItem)));
                //       },
                //       style: ElevatedButton.styleFrom(
                //         backgroundColor: Colors.grey.shade400,
                //         elevation: 0,
                //         padding: EdgeInsets.fromLTRB(sy(5), sy(4), sy(5), sy(4)),
                //         foregroundColor: ThemePrimary,
                //         tapTargetSize: MaterialTapTargetSize.shrinkWrap,
                //         minimumSize: Size.zero,
                //         shape: RoundedRectangleBorder(
                //             borderRadius: BorderRadius.circular(sy(3))
                //         ),
                //         surfaceTintColor: fc_bg,
                //       ),
                //     ),
                //   ),
                // ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  _screenBody() {
    return Container(
      width: MediaQuery.of(context).size.width,
      height: MediaQuery.of(context).size.height,
      color: fc_bg,
      padding: EdgeInsets.fromLTRB(sy(10), sy(10), sy(10), sy(5)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
            Expanded(
                child: Container(
                  child: SingleChildScrollView(
                    controller: _scrollController,
                    scrollDirection: Axis.vertical,
                    physics: BouncingScrollPhysics(),
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.start,
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        donorImageWidget(),
                        titleWidget('Address of the Recipient', donorItem['b_hospital'] ),
                        cardInfoWidget('Blood Group', donorItem['b_grp']),
                        SizedBox(height: sy(2),),
                        cardInfoWidget('Age of Recipient', donorItem['b_age']+' Age'),
                        SizedBox(height: sy(2),),
                        cardInfoWidget('Contact number', donorItem['b_phone']),
                        SizedBox(height: sy(2),),
                        cardInfoWidget('Posted Date', donorItem['b_dated'].substring(0, 10)),
                        SizedBox(
                          height: sy(10),
                        ),
                        titleWidget('Description', donorItem['b_desc'] ),
                        if(donorItem['b_status']=='1')donatedPersonInfo(),
                        if(donorItem['b_status']=='0')notYetDonated(),
                      ],
                    ),
                  ),
                )
            ),
        ],
      ),
    );
  }

  notYetDonated() {
    return Container(
      width: MediaQuery.of(context).size.width,
      child: Container(
        child: Text(
         'Not Yet Donated!!',
         style: TextStyle(
           color: fc_1,
           fontSize: sy(l),
           fontWeight: FontWeight.bold,
         ),
        ),
      ),
    );
  }

  donatedPersonInfo() {
    return Container(
      width: MediaQuery.of(context).size.width,
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            'Details of the Donated Person',
            style: TextStyle(
              fontSize: sy(n+1),
              color: fc_1,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(
            height: sy(5),
          ),

          Row(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                padding: EdgeInsets.all(sy(2)),
                decoration: decoration_round(
                    fc_stock, sy(4), sy(4), sy(4), sy(4)),
                child: Image.asset(
                  'assets/images/user11.jpg',
                  width: sy(40),
                  height: sy(40),
                ),
              ),
              SizedBox(
                width: sy(10),
              ),
              Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [

                  SizedBox(
                    height: sy(3),
                  ),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    crossAxisAlignment: CrossAxisAlignment.center,
                    children: [
                      Text(
                        donorItem['bd_name'],
                        style: TextStyle(
                          fontSize: sy(n+1),
                          color: fc_1,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      SizedBox(
                        width: sy(3),
                      ),
                      Icon(
                        Icons.verified_rounded,
                        size: sy(l),
                        color: Colors.green,
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
                          Icon(Icons.phone,color: ThemePrimary,size: sy(n+1),),
                          SizedBox(
                            width: sy(3),
                          ),
                          Text(
                            donorItem['bd_phone'].toString(),
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
                          Icon(Icons.location_city,color:ThemePrimary,size: sy(n+1),),
                          SizedBox(
                            width: sy(3),
                          ),
                          Text(
                            donorItem['bd_city'].toString(),
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
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }

  donorImageWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(10)),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            width: Width(context)*0.30,
            height: Width(context)*0.25,
            child: ClipRRect(
              borderRadius: BorderRadius.circular(sy(8)),
              child: CustomeImageView(
                width: Width(context),
                height: Width(context),
                image:Urls.imageLocation+donorItem['b_image'].toString(),
                placeholder: Urls.DummyImageBanner,
                fit: BoxFit.cover,
                blurBackground: false,
              ),
            ),
          ),
          SizedBox(
            width: sy(8),
          ),
          Expanded(
            child: Row(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.center,
              children: [
                Expanded(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.start,
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        donorItem['b_name'].toString().toUpperCase(),
                        style: TextStyle(
                          color: fc_1,
                          fontSize: sy(n+1),
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      Text(
                        '#NSSBD00'+donorItem['b_id'],
                        style: TextStyle(
                          color: fc_2,
                          fontSize: sy(s+1),
                          fontWeight: FontWeight.w400,
                        ),
                      ),
                      SizedBox(
                        height: sy(2),
                      ),
                      Row(
                        children: [
                          Icon(Icons.my_location_outlined,
                            size: sy(n),
                            color: fc_1,
                          ),
                          SizedBox(
                            width: sy(3),
                          ),
                          Text(
                            donorItem['b_city'],
                            style: TextStyle(
                              color: fc_1,
                              fontSize: sy(s),
                              fontWeight: FontWeight.w600,
                            ),
                          ),
                        ],
                      ),
                      SizedBox(
                        height: sy(2),
                      ),
                      Container(
                        padding: EdgeInsets.fromLTRB(sy(3), sy(1), sy(3), sy(1)),
                        decoration: BoxDecoration(
                          color: ThemePrimary,
                          borderRadius: BorderRadius.circular(sy(3)),
                        ),
                        child: Text((donorItem['b_status']=='1')?'Donated':'Not Donated',
                        style: TextStyle(
                          color: fc_bg,
                          fontSize: sy(s),
                          fontWeight: FontWeight.w400,
                        ),),
                      ),
                    ],
                  ),
                ),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  crossAxisAlignment: CrossAxisAlignment.center,
                  children: [
                    Container(
                      width: sy(18),
                      height: sy(18),
                      padding: EdgeInsets.fromLTRB(sy(2), sy(2), sy(2), sy(2)),
                      decoration: BoxDecoration(
                        color: ThemePrimary,
                        borderRadius: BorderRadius.circular(sy(50)),
                      ),
                      child: Icon(Icons.call,
                        size: sy(l),
                        color: fc_bg,
                      ),
                    ),
                    SizedBox(
                      width: sy(15),
                    ),
                    Container(
                      width: sy(18),
                      height: sy(18),
                      padding: EdgeInsets.fromLTRB(sy(2), sy(2), sy(2), sy(2)),
                      decoration: BoxDecoration(
                        color: ThemePrimary,
                        borderRadius: BorderRadius.circular(sy(50)),
                      ),
                      child: Icon(Icons.location_pin,
                        size: sy(l),
                        color: fc_bg,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),

        ],
      ),
    );
  }

  titleWidget(String title, String value) {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(15)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title,
            style: TextStyle(
              fontSize: sy(n+1),
              color: fc_1,
              fontWeight: FontWeight.bold,
            ),
          ),
          SizedBox(
            height: sy(5),
          ),
          Text(
            value,
            style: TextStyle(
              fontSize: sy(n-1),
              color: fc_2,
              fontWeight: FontWeight.w500,
            ),
          ),
        ],
      ),
    );
  }

  cardInfoWidget(String title, String val) {
    return Container(
      width: MediaQuery.of(context).size.width,
      height: sy(20),
      padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(10), sy(0)),
      decoration: BoxDecoration(
        color: fc_stock,
        borderRadius: BorderRadius.circular(sy(3)),
      ),
      child: Row(
        children: [
          Expanded(
            child: Text(title,
            style: TextStyle(
              fontSize: sy(s),
              color: fc_1,
              fontWeight: FontWeight.w400,
            ),),
          ),
          Text(val,
            style: TextStyle(
              fontSize: sy(s),
              color: ThemePrimary,
              fontWeight: FontWeight.w500,
            ),),
        ],
      ),
    );
  }
}

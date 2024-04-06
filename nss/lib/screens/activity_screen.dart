import 'package:flutter/material.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/utils/style_sheet.dart';

import '../components/custom_image_viewer.dart';
import '../utils/urls.dart';

class ActivityScreen extends StatefulWidget {
  var arrayList;
  ActivityScreen({Key? key, required this.arrayList});

  @override
  State<ActivityScreen> createState() => _ActivityScreenState();
}

class _ActivityScreenState extends State<ActivityScreen> with RelativeScale{
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  ScrollController _scrollController = ScrollController();

  var arrItem;

  @override
  void initState() {
    super.initState();
    arrItem = widget.arrayList;
  }

  @override
  void dispose() {
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return MaterialApp(
      home: SafeArea(
        top: false,
        child: Scaffold(
          key: _scaffoldKey,
          extendBody: true,
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
                arrItem['a_name'].toUpperCase(),
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
                  top: 0,
                  left: 0,
                  right: 0,
                  bottom: 0,
                  child: _screenBody(),
                ),

                Positioned(
                  left: sy(5),
                  child: Container(
                    padding: EdgeInsets.fromLTRB(sy(10), sy(8), sy(10), sy(5)),
                    width: Width(context),
                    alignment: Alignment.centerLeft,
                    child:  Image.asset('assets/images/nss_logo.png', height: sy(35),fit: BoxFit.cover,),
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  _screenBody() {
    return Container(
      color: fc_bg,
      width: MediaQuery.of(context).size.width,
      height: MediaQuery.of(context).size.height,
      padding: EdgeInsets.fromLTRB(sy(10), sy(5), sy(10), sy(5)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Expanded(
            child: SingleChildScrollView(
              physics: BouncingScrollPhysics(),
              scrollDirection: Axis.vertical,
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  activityImageWidget(),
                  titleWidget('Description'),
                  descriptionWidget(),
                  galleryWidget(),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  descriptionWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(5)),
      child: Text(arrItem['a_desc'],
      style: TextStyle(
        fontSize: sy(s+1),
        color: fc_1,
        fontWeight: FontWeight.w400,
      ),),
    );
  }

  activityImageWidget() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          Container(
            width: MediaQuery.of(context).size.width * 0.90,
            height: MediaQuery.of(context).size.height * 0.25,
            padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(5)),
            margin: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(5)),
            child: ClipRRect(
              borderRadius: BorderRadius.circular(sy(5)),
              child: Container(
                height: MediaQuery.of(context).size.height,
                width: MediaQuery.of(context).size.width,
                child: CustomeImageView(
                  image:Urls.imageLocation+arrItem['a_image'].toString(),
                  placeholder: Urls.DummyImageBanner,
                  fit: BoxFit.cover,
                  blurBackground: false,
                ),
              ),
            ),
          ),
          Text(
            arrItem['a_name'].toUpperCase(),
            style: TextStyle(
              fontWeight: FontWeight.bold,
              fontSize: sy(l-1),
              color: fc_1,
            ),
          ),
          Container(
            width: MediaQuery.of(context).size.width * 0.30,
            padding: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
            child: Divider(
              thickness: sy(0.8),
              color: ThemePrimary,
            ),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Icon(
                  Icons.location_city,
                  size: sy(n-1),
                  color: fc_2,
                ),
              SizedBox(
                width: sy(5),
              ),
              Text(
                arrItem['a_location'],
                style: TextStyle(
                  fontWeight: FontWeight.w400,
                  fontSize: sy(n-1),
                  color: fc_1,
                ),
              ),
            ],
          ),
          SizedBox(
            height: sy(3),
          ),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Icon(
                Icons.access_time_outlined,
                size: sy(n-1),
                color: fc_2,
              ),
              SizedBox(
                width: sy(5),
              ),
              Text(
                arrItem['a_date'],
                style: TextStyle(
                  fontWeight: FontWeight.w400,
                  fontSize: sy(n-1),
                  color: fc_1,
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }

  titleWidget(String title) {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(0), sy(5), sy(0), sy(5)),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Text(
            title.toUpperCase(),
            style: TextStyle(
              fontSize: sy(n+1),
              color: fc_1,
              fontWeight: FontWeight.bold,
            ),
          ),
        ],
      ),
    );
  }

  galleryWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      child: Column(
        children: [
          titleWidget('Gallery'),
          SingleChildScrollView(
            physics: BouncingScrollPhysics(),
            scrollDirection: Axis.horizontal,
            child: Row(
              mainAxisAlignment: MainAxisAlignment.start,
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Container(
                  width: MediaQuery.of(context).size.width *0.8,
                  height: MediaQuery.of(context).size.height* 0.22,
                  child: Stack(
                    children: [
                      Positioned(
                          top: sy(5),
                          left: sy(0),
                          right: sy(0),
                          bottom: sy(0),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(sy(5)),
                            child: Container(
                              height: MediaQuery.of(context).size.height,
                              width: MediaQuery.of(context).size.width,
                              child: CustomeImageView(
                                image:Urls.imageLocation+arrItem['image1'].toString(),
                                placeholder: Urls.DummyImageBanner,
                                fit: BoxFit.cover,
                                blurBackground: false,
                              ),
                            ),
                          )),
                    ],
                  ),
                ),
                SizedBox(
                  width: sy(10),
                ),
                Container(
                  width: MediaQuery.of(context).size.width *0.8,
                  height: MediaQuery.of(context).size.height* 0.22,
                  child: Stack(
                    children: [
                      Positioned(
                          top: sy(5),
                          left: sy(0),
                          right: sy(0),
                          bottom: sy(0),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(sy(5)),
                            child: Container(
                              height: MediaQuery.of(context).size.height,
                              width: MediaQuery.of(context).size.width,
                              child: CustomeImageView(
                                image:Urls.imageLocation+arrItem['image2'].toString(),
                                placeholder: Urls.DummyImageBanner,
                                fit: BoxFit.cover,
                                blurBackground: false,
                              ),
                            ),
                          )),
                    ],
                  ),
                ),
                SizedBox(
                  width: sy(10),
                ),
                Container(
                  width: MediaQuery.of(context).size.width *0.8,
                  height: MediaQuery.of(context).size.height* 0.22,
                  child: Stack(
                    children: [
                      Positioned(
                          top: sy(5),
                          left: sy(0),
                          right: sy(0),
                          bottom: sy(0),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(sy(5)),
                            child: Container(
                              height: MediaQuery.of(context).size.height,
                              width: MediaQuery.of(context).size.width,
                              child: CustomeImageView(
                                image:Urls.imageLocation+arrItem['image3'].toString(),
                                placeholder: Urls.DummyImageBanner,
                                fit: BoxFit.cover,
                                blurBackground: false,
                              ),
                            ),
                          )),
                    ],
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );

  }

}

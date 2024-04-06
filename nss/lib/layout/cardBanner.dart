import 'package:flutter/material.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/router/open_screen.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:nss/utils/urls.dart';
import '../components/custom_image_viewer.dart';
import '../screens/activity_screen.dart';

class cardBanner extends StatefulWidget {
  var arrayList;
  int i;
  GestureTapCallback favBtn;
  cardBanner({Key? key, required this.arrayList, required this.favBtn, required this.i}):super(key: key);

  @override
  State<cardBanner> createState() => _cardBannerState();
}

class _cardBannerState extends State<cardBanner> with RelativeScale {
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
      padding: EdgeInsets.fromLTRB(sy(10), sy(0), sy(10), sy(0)),
      child: Column(
          children: [
            Container(
              width: MediaQuery.of(context).size.width,
              height: MediaQuery.of(context).size.height* 0.22,
              child: Stack(
                children: [
                  Positioned(
                      top: sy(5),
                      left: sy(0),
                      right: sy(0),
                      bottom: sy(0),
                      child: GestureDetector(
                        onTap: () {
                          apiTest(arrItem.toString());
                          Navigator.push(context, OpenScreen(widget: ActivityScreen(arrayList: arrItem)));
                        },
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
                      )),

                  Positioned(
                    left: sy(12),
                    bottom: sy(10),
                    child: Text(arrItem['a_name'].toString(), style: TextStyle(
                    color: Colors.white,
                    fontSize: sy(s),
                      overflow: TextOverflow.ellipsis,),),
                  )
                ],
              ),
            ),

          ],
      ),
    );
  }
}

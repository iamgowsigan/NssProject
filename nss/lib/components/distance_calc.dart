import 'package:flutter/cupertino.dart';
import 'package:geolocator/geolocator.dart';
import 'package:nss/utils/app_settings.dart';
import 'package:provider/provider.dart';

class DistanceCalc{

  static map(BuildContext context,String location,){

    double distanceInMeters=0;
    List maploc=location.toString().split(',');
    double mylat= 0;
    double mylong= 0;

    try{
      mylat= double.parse( Provider.of<AppSetting>(context, listen: false).devicelat);
    }catch(e){
      mylat= 24.4539;
    }

    try{
      mylong= double.parse(Provider.of<AppSetting>(context, listen: false).devicelng);
    }catch(e){
      mylong=54.3773;
    }

    if(maploc.length>1){
      distanceInMeters = Geolocator.distanceBetween(mylat,mylong, double.parse(maploc[0].toString()), double.parse(maploc[1].toString()));
    }

    if(distanceInMeters>1000){
      return (distanceInMeters/1000).toStringAsFixed(2)+' '+'km';
      //apiTest(mylat.toString()+','+mylong.toString());
      //return mylong.toString()+'km';

    }else{
      return (distanceInMeters).toStringAsFixed(2)+' '+'m';
    }

  }
}
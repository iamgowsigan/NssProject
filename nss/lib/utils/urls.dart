
import 'const.dart';

class Urls {


  // static var iP = "http://172.18.96.72/"; //hostel
  // static var iP = "http://192.168.1.6/"; //home
  static var iP = "http://192.168.1.9/";
   static var address = iP + "FlutterProjects/NssProject/Site/API/mobile_api/";
   static var imageLocation =  iP+"FlutterProjects/NssProject/Site/mec/";

  //static var iP = "http://192.168.1.9/";
  //static var address = iP + "API/mobile_api/";
  //static var imageLocation =  iP+"mec/";


  static var DummyLogo = "assets/images/logo.png";
  static var DummyImageBanner = "assets/images/banner.jpg";
  static var CurrencyAPI = "https://data.fixer.io/api/latest?access_key=${Const.FIXER_API}&base=AED&symbols=AED,INR,KWD,EGP,OMR,QAR,SAR,BHD";



  //OTP and Registration
  static var validation = address + "validation.php";
  static var GetUser = address + "otp-getuser.php";
  static var GetUserEmail = address + "otp-getemailuser.php";
  static var UpdateUser = address + "otp-updateuser.php";


  //UserProfile
  static var UpdateProfile = address + "update-profile.php";
  static var UpdateImage = address + "update-user-image.php";
  static var UploadProfileField = address + "update-profile-field.php";

  static var Dashboard = address + "dashboard.php";
  static var ListStudents = address + "list-students.php";
  static var AddAttendance = address + "add-attendance.php";



  //Vendor
  static var VendorDashboard = address + "vendor-dashboard.php";
  static var VendorProducts = address + "vendor-products.php";
  static var AddProduct = address + "add-product.php";
  static var UpdateProduct = address + "update-product.php";
  static var AddImage = address + "add-image.php";
  static var DeleteContent = address + "delete-content.php";
  static var AddVariant = address + "add-variant.php";
  static var ProductStatus = address + "product-status.php";
  static var VendorReview = address + "vendor-review.php";
  static var AddReviewReply = address + "add-reviewreply.php";

  static var VendorCoupons = address + "vendor-coupons.php";
  static var AddCoupon = address + "add-coupon.php";
  static var CouponStatus = address + "coupon-status.php";
  static var VendorUser = address + "vendor-user.php";

  //Vendor Order List
  static var VendorOrderList=address+"vendor-orders.php";
  static var VendorOrderView=address+"vendor-order-view.php";
  static var OrderStatus=address+"order-status.php";

  //User
  static var AddLike = address + "add-like.php";
  static var ProductList = address + "product-list.php";
  static var ProductView = address + "product-view.php";
  static var AddReview = address + "add-review.php";
  static var UploadFile = address + "upload-file.php";
  static var AddCart = address + "add-cart.php";

  //Cart
  static var Cart = address + "cart.php";
  static var ModifyCart=address+"modify-cart.php";

  //USER Address
  static var AddressList=address+"address-list.php";
  static var AddAddress=address+"add-address.php";
  static var DeleteAddress=address+"delete-address.php";

  //Booking
  static var AddBooking=address+"booking.php";
  static var AddPreBooking=address+"pre-booking.php";

  //Order List
  static var OrderList=address+"order-list.php";
  static var OrderView=address+"order-view.php";

  //search
  static var searchList = address + "search-list.php";
  static var FavList = address + "fav-list.php";

  static var Subscribe = address + "subscribe.php";
  static var VendorPayout = address + "vendor-payout.php";


}

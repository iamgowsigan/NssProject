<?php

function forminput($lable = '', $key = '', $val = '', $required = '', $size = '6', $type = 'text', $placeholder = '')
{

	echo '<div class="col-' . $size . '">
		<div class="form-group mb-3">
		<label for="validationCustom01">' . $lable . '</label>
		<input type="' . $type . '" class="form-control" name="' . $key . '" value="' . $val . '" placeholder="' . $placeholder . '"  ' . $required . '>
		</div>
		</div>';

}


function formtextarea($lable = '', $key = '', $val = '', $required = '', $size = '6', $type = 'text')
{

	echo '<div class="col-' . $size . '">
		<div class="form-group mb-3">
		<label for="validationCustom01">' . $lable . '</label>
		<textarea class="form-control" name="' . $key . '" ' . $required . '>' . $val . '</textarea>
		</div>
		</div>';

}
function formhtml($lable = '', $key = '', $val = '', $required = '', $size = '6', $type = 'text')
{

	echo '<div class="col-' . $size . '">
		<div class="form-group mb-3">
		<label for="validationCustom01">' . $lable . '</label>
		<textarea class="form-control-file" id="summernote-' . $type . '" name="' . $key . '" >' . $val . '</textarea>
		</div>
		</div>';

}





function formimage($lable = '', $key = '', $val = '', $required = '', $size = '6', $oldkey = '', $width = '40', $height = '50', $fit = 'cover', $multi = false, $filetype = '')
{

	include 'includes/config.php';
	$errimg = "'assets/images/bg-auth.jpg'";

	$filetyperules = '';
	if ($filetype == 'pdf') {
		$filetyperules = 'accept="application/' . $filetype . '"';
	}

	if ($filetype == 'mp4') {
		$filetyperules = 'accept="video/mp4,video/x-m4v,video/*"';
	}


	$multiclass = '';
	if ($multi == true) {
		$multiclass = 'multiple';
		$key = $key . '[]';
	}

	echo '<div class="col-' . $size . '">
		<div class="form-group mb-3">
		<label for="validationCustom03">' . $lable . '</label>
		<input type="file" class="form-control-file" id="exampleInputFile" name="' . $key . '" ' . $multiclass . ' ' . $filetyperules . '>';



	if ($val != '') {

		if ($filetype == '') {
			echo '<br><img src="' . $imgloc . $val . '"  class="img-fluid avatar-md rounded" onerror="this.src=' . $errimg . '" style="width:' . $width . 'px;height:' . $height . 'px;object-fit:' . $fit . '">';
		} else if ($filetype == 'mp4') {

			echo '<br><video width="200" height="130" controls="controls" preload="metadata" style="border-radius:5px;object-fit: cover;
			"><source src="' . $imgloc . $val . '" type="video/mp4"></video>';
		} else {

			echo '<br><a href="' . $imgloc . $val . '" target="_blank" ><span class="text-success">' . $val . ' &nbsp; ( view file )</span></a>';
		}

		echo '<input type="hidden" name="' . $oldkey . '" value="' . $val . '" >';


	}




	echo '</div>
		</div>';

}



function formselect($lable = '', $key = '', $arraylist = array(), $required = '', $size = '6', $selected = '', $value = '', $option = '')
{

	echo '<div class="col-' . $size . '">
		<div class="form-group mb-3">
		<label for="validationCustom01">' . $lable . '</label>
		
		<select class="cate form-control select2" data-toggle="select2" name="' . $key . '" ' . $required . ' >
		<option value="" selected>Choose...</option>';


	foreach ($arraylist as $menu) {
		$isselected = '';
		if ($menu[$value] == $selected) {
			$isselected = 'selected';
		} else {
			$isselected = '';
		}

		echo '<option value="' . $menu[$value] . '" ' . $isselected . ' >' . $menu[$option] . '</option>';

	}

	echo '
		</select>
		</div>
		</div>';

}


function formmulti($lable = '', $key = '', $arraylist = array(), $required = '', $size = '6', $selected = '', $value = '', $option = '')
{

	echo '<div class="col-' . $size . '">
		<div class="form-group mb-3">
		<label for="validationCustom01">' . $lable . '</label>
		
		<select class="cate form-control select2" data-toggle="select2" multiple name="' . $key . '[]" ' . $required . ' >';

	$oldValueList = explode(",", $selected);
	foreach ($arraylist as $menu) {
		$isselected = '';
		if (in_array($menu[$value], $oldValueList)) {
			$isselected = 'selected';
		} else {
			$isselected = '';
		}

		echo '<option value="' . $menu[$value] . '" ' . $isselected . ' >' . $menu[$option] . '</option>';

	}

	echo '
		</select>
		</div>
		</div>';


}


function text($lable = '', $strong = false, $small = false, $badge = false, $lighten = false, $outline = false, $color = 'muted', $icon = '')
{

	$sstrong = '';
	$estrong = '';
	if ($strong == true) {
		$sstrong = '<strong>';
		$estrong = '</strong>';
	}

	$ssmall = '';
	$esmall = '';
	if ($small == true) {
		$ssmall = '<small>';
		$esmall = '</small>';
	}

	$colorclass = '';
	if ($color != '') {
		$colorclass = 'text-' . $color;
	}


	$badgeclass = '';
	if ($badge == true) {

		$outlineclass = '';
		$lightclass = '';
		if ($lighten == true) {
			$lightclass = '-lighten';
		}

		if ($outline == true) {
			$outlineclass = '-outline';
			$lightclass = '';
		}

		$badgeclass = 'badge badge' . $outlineclass . '-' . $color . $lightclass;
		$colorclass = '';
	}
	$iconclass = '';
	if ($icon != '') {
		$iconclass = '<i class="' . $icon . '"></i>&nbsp;&nbsp;';

	}

	if ($lable != '' && $lable != 'null' && $lable != 'NULL') {
		echo '<span class="' . $badgeclass . $colorclass . '">'

			. $ssmall
			. $sstrong
			. $iconclass
			. $lable
			. $estrong
			. $esmall

			. '</span>';
	}

}

function image($lable = '', $width = '40', $height = '50', $fit = 'cover')
{

	include 'includes/config.php';
	$errimg = "'assets/images/img-placeholder.png'";

	if ($lable != '' && $lable != null) {

		$fileNameParts = explode('.', $lable);
		$ext = end($fileNameParts);
		$ext = strtolower($ext);

		if($ext=='jpg' || $ext=='png' || $ext=='gif' || $ext=='webp'|| $ext=='tiff'|| $ext=='bmp' || $ext=='jpeg'  || $ext=='avif'){

			echo '<img src="' . $imgloc . $lable . '"  style="width:' . $width . 'px;height:' . $height . 'px;object-fit:' . $fit . '" class="img-thumbnail rounded" onerror="this.src=' . $errimg . '">';
		}else if($ext=='mp4' || $ext=='webm' || $ext=='mkv' ){

			echo '<video width="' . $width . '" height="' . $height . '"   preload="metadata" style="border-radius:5px;object-fit: cover;
			"><source src="' . $imgloc . $lable . '" type="video/mp4"></video>';
		}
		

	} else {
		echo '';

	}




}

function active($lable = '', $id = '')
{
	$url = $_SERVER['PHP_SELF'] . '?scid=' . $id . '&&action=status&val=';
	if ($lable) {
		echo '<a href="' . $url . 'N"><span class="badge badge-outline-success">Not Donated</span></a>';

	} else {

		echo '<a href="' . $url . 'D"><span class="badge badge-outline-danger">Donated</span></a>';
	}

}


function action($id = '', $buttonlist = 'V,E,D,R', $name = '')
{
	$edit = $_SERVER['PHP_SELF'] . '?eid=' . $id . '&&edit=1';
	$delete = $_SERVER['PHP_SELF'] . '?scid=' . $id . '&&action=del';
	$errormsg = 'Are you sure you want to delete?';




	echo '<div class="btn-group">
		<button type="button" class="btn btn-light dropdown-toggle btn-sm" data-toggle="dropdown" aria-expanded="false">More <span class="caret"></span> </button>
		<div class="dropdown-menu">';

	if (strpos($buttonlist, "V") !== false)
		echo '<a class="dropdown-item" href="view-' . basename($_SERVER["SCRIPT_NAME"]) . '?sid=' . $id . '&sname=' . $name . '">View</a>';

	if (strpos($buttonlist, "E") !== false)
		echo '<a class="dropdown-item" href="' . $edit . '" >Edit</a>';

	if (strpos($buttonlist, "R") !== false)
		echo '<a class="dropdown-item" href="' . $edit . '" >Reply</a>';

	if (strpos($buttonlist, "D") !== false)
		echo '<a class="dropdown-item" href="' . $delete . '" onclick="return confirm(' . $errormsg . ');" >Delete</a>';



	echo '</div></div>';

}


function statusactive($id = '', $value = '', $buttonlist)
{
	$buttonlable = 'Active';
	$buttoncolor = 'success';
	if ($value == 'A') {
		$buttonlable = 'Active';
		$buttoncolor = 'success';
	}
	if ($value == 'P') {
		$buttonlable = 'Pending';
		$buttoncolor = 'info';
	}
	if ($value == 'E') {
		$buttonlable = 'Deleted';
		$buttoncolor = 'danger';
	}
	if ($value == 'C') {
		$buttonlable = 'Cancelled';
		$buttoncolor = 'danger';
	}


	if ($value == 'D') {
		$buttonlable = 'Deactive';
		$buttoncolor = 'warning';
	}
	if ($value == 'H') {
		$buttonlable = 'Hide';
		$buttoncolor = 'info';
	}

	if ($value == 'O') {
		$buttonlable = 'Out of Stock';
		$buttoncolor = 'warning';
	}
	if ($value == 'L') {
		$buttonlable = 'Live';
		$buttoncolor = 'success';
	}

	$url = $_SERVER['PHP_SELF'] . '?action=update&' . '&scid=' . $id . '&val=';
	echo '<button type="button" class="btn btn-sm btn-' . $buttoncolor . ' dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $buttonlable . '</button>';



	echo '<div class="dropdown-menu">';

	if (strpos($buttonlist, "A") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'A" >Active</a>';
	if (strpos($buttonlist, "L") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'L" >Live</a>';
	if (strpos($buttonlist, "D") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'D">Deactive</a>';
	if (strpos($buttonlist, "E") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'E">Delete</a>';
	if (strpos($buttonlist, "H") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'H" >Hide</a>';
	if (strpos($buttonlist, "C") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'C" >Cancel</a>';
	if (strpos($buttonlist, "P") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'P" >Pending</a>';
	if (strpos($buttonlist, "O") !== false)
		echo '<a class="dropdown-item" href="' . $url . 'O" >Out of Stock</a>';


	echo '</div>';




}


function ratingBar($star = '0', $count = '0', $showcount = true)
{

	$resStar = 0;
	if ($count == '0' || $count == null) {
		$resStar = 0;
	} else {
		$resStar = $star / $count;
	}

	if ($showcount == true) {

		echo '<small><i class="uil uil-user"></i>&nbsp;' . $count . ' Count</small><br>';
	}

	echo '<div class="rateit rateit-mdi" data-rateit-mode="font"
	data-rateit-icon=""
	data-rateit-value="' . $resStar . '"
	data-rateit-ispreset="true" data-rateit-readonly="true"
	style="font-size:15px">
</div>';

}


function cardMenu($lable = '', $number = '', $url = '', $icon = '', $size = '6', $iconcolor, $bottomtext = 'Add / Manage')
{
	$buttonlink = '';
	if ($url != '') {
		$buttonlink = '<span class="text-success mr-2"><i class="mdi mdi-dresser-outline"></i>&nbsp;&nbsp;' . $bottomtext . '</span>';

	}

	if ($size >= 3) {
		$head = '<h3 class="text-dark mt-2 mb-2">' . $number . '</h3>';

	} else {
		$head = '<h4 class="text-dark mt-2 mb-2">' . $number . '</h4>';
	}

	echo '<div class="col-xl-' . $size . ' col-lg-' . $size . '">
		<div class="card widget-flat h-75">
		<div class="card-body ">
		
		<div class="float-right">
		<i class="' . $icon . ' widget-icon bg-' . $iconcolor . ' rounded-circle text-white"></i>
		</div>
		<h5 class="text-dark font-weight-normal mt-0" title="Number of Feeds">' . $lable . '</h5>
		
		' . $head . '
		<p class=" text-muted pb-1">
		
		<small><a href="' . $url . '"  style="border:none;background-color:white" >' . $buttonlink . '</a></small>
		</p>
		</div> 
		</div> 	
		</div> ';

}



function addStart($size = 'lg')
{


	echo '<div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog  modal-' . $size . ' modal-dialog-centered">
			<div class="modal-content">
				
				<div class="modal-body">
					<div class="text-center mt-2 mb-4">
						<a href="#" class="text-success">
							<span><img src="assets/images/logo-dark.png" alt="" height="30"></span>
						</a>
					</div>
					
					<form class="pl-3 pr-3"  method="post" enctype="multipart/form-data">
						<div class="row">';

}

function addEnd()
{


	echo '</div>
															
		<div class="form-group text-center"><br><br>
			<button class="btn btn-primary" type="submit" name="submit">Add New</button>
		</div>
		
	</form>
	
</div>
</div>
</div>
</div>';

}



//'.$val.'
?>
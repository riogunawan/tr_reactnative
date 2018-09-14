$(document).ready(function() {
	$(".mn-drop-admin>a, .mn-admin").addClass("active");
    $(".mn-drop-admin>.dropdown-collapse").addClass("toggledropdown");

	$(".s2").select2({
		theme: "bootstrap",
        width: "100%",
        placeholder: "pilih...",
    });

	getKabupaten();
	$(".id_level").change(getKabupaten);

    fileInputInit();

	$(".btn-proses").click(function(e) {
        e.preventDefault();
        $(".form").submit();
    });

	$('form').validate({
		// debug: true,
		ignore: [],
		errorClass: 'error',
		// showErrors: function(errorMap, errorList) {},
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();

			if (errors) {
				var errors = "";

				if (validator.errorList.length > 0) {
					for (x = 0; x < validator.errorList.length; x++) {
						errors += "<div class='text-danger'>" + validator.errorList[x].message + "</div>";
					}
				}
				swal({
					title: "Error Messages",
					text: errors,
					// animation: "slide-from-top",
					type: "error",
					confirmButtonColor: "#fb483a",
					html: true
				});
			}
			validator.focusInvalid();
		},
		rules: {
			nama_lengkap: { required: true },
			username: { 
				required: true,
				remote: {
					url: "<?php echo base_url();?>admin/check",
					type: "post",
					data: {
						id : function() {
							return $( ".id" ).val()
						},
						username : function() {
							return $( ".username" ).val()
						}
					}
				}
			},
			pass: {
				required: function () {
					var id = $(".id").val();
					if (id > 0) {
						return false;
					} else {
						return true;
					}
				}
			},
			pass_confirm: { equalTo: ".pass" },
			id_level: { required: true },
			kabupaten: {
				required: function () {
					var id = $(".id_level").val();
					if (id != 2) {
						return false;
					} else {
						return true;
					}
				}
			},
		},
		messages: {
			nama_lengkap: { required: "Nama Lengkap tidak boleh kosong" },
			username: {
				required:"Username Harus Di isi",
				remote:"Username sudah digunakan"
			},
			id_level:{ required:"Level Harus Di isi" },
			pass:{ required:"Password Harus Di isi" },
			pass_confirm:{ equalTo:"Konfirmasi Password tidak sama dengan Password" },
			kabupaten:{ required:"Kabupaten Harus Di isi" },
		},
   	});

});

function getKabupaten () {
    var parent = $(".id_level").val();

    if (parent == 2) {
        $(".kabupaten_hide").show();
    } else {
        $(".kabupaten_hide").hide();
    }
}

function fileInputInit () {
	var file = $(".avatar").data("avatar");
	var imageLink = "<?php echo base_url() ?>assets/themes/admin/images/logo-dummy.jpg";
	if (file != "") {
		imageLink = "<?php echo base_url() ?>assets/upload/admin/" + file;
	}

	var fileInput = {
	   theme 		   : "fa",
	   autoReplace     : true,
	   showUpload      : false,
	   showCaption     : false,
	   showBrowse      : true,
	   showCancel      : false,
	   browseLabel     : "Browse",
	   allowedFileExtensions: ["jpg", "bmp", "jpeg", "png", "gif"],
	   previewSettings : {
		   image: { width: "98.5%", height: "auto" },
		   pdf: { width: "98.5%", height: "auto" },
	   },
	   initialPreview: [
		   "<img src='"+ imageLink +"' class='file-preview-image' style='width: 100%; height: 130%' alt='Default Image' title='Default Image'>",
	   ]
   };

   $("#avatar").fileinput(fileInput);
}
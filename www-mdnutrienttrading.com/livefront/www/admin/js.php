<script language="javascript">

function openWinLeft( url ) {
  var WindowInfo= 'width=700,height=700,status=no,directories=no,toolbar=no,location=no,scrollbars=yes,resizable=yes';
  var newWindow = window.open( url , 'LeftNav' , WindowInfo  );
  newWindow.focus();

}


function openWinRight( url ) {
  var WindowInfo= 'width=650,height=400,status=no,directories=no,toolbar=no,location=no,scrollbars=yes,resizable=yes';
  var newWindow = window.open( url , 'RightNav' , WindowInfo  );
  newWindow.focus();

}


function openWinMoreInfo( url ) {
  var WindowInfo= 'width=650,height=400,status=no,directories=no,toolbar=no,location=no,scrollbars=yes,resizable=yes';
  var newWindow = window.open( url , 'RightNav' , WindowInfo  );
  newWindow.focus();

}


function refresh() {
   self.opener.history.go(0);
   self.close();
}


function close_and_refresh() {
   <?php if ($pi != "") { $refresh_page .= "?i=$pi"; } ?>
   <?php if ($alert_msg != "") { echo "alert( '$alert_msg' );\n"; } ?>
   window.opener.location.href = '<?php echo $refresh_page; ?>';
   self.close();
   return true;
}


function cancel_and_close() {
   self.close();
}


</script>



<?php
   session_start();
   error_reporting(0);
   define('ADMIN_PASS', '5f4dcc3b5aa765d61d8327deb882cf99');
   $session_timeout = 600;
   $mysql_server = 'admin';
   $mysql_username = 'admin';
   $mysql_password = 'admin';
   $mysql_database = '';
   $mysql_table = 'USERS';
   $admin_password = isset($_COOKIE['admin_password']) ? $_COOKIE['admin_password'] : '';
   if (empty($admin_password))
   {
      if (isset($_POST['admin_password']))
      {
         $admin_password = md5($_POST['admin_password']);
         if ($admin_password == ADMIN_PASS)
         {
            setcookie('admin_password', $admin_password, time() + $session_timeout);
         }
      }
   }
   else
   if ($admin_password == ADMIN_PASS)
   {
      setcookie('admin_password', $admin_password, time() + $session_timeout);
   }
   $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
   $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
   $username = isset($_POST['username']) ? $_POST['username'] : '';
   $fullname = isset($_POST['fullname']) ? $_POST['fullname'] : '';
   $email = isset($_POST['email']) ? $_POST['email'] : '';
   $active = isset($_POST['active']) ? $_POST['active'] : 0;
   $role = isset($_POST['role']) ? $_POST['role'] : '';
   $db = mysqli_connect($mysql_server, $mysql_username, $mysql_password);
   if (!$db)
   {
      die('Failed to connect to database server!<br>'.mysqli_error($db));
   }
   mysqli_select_db($db, $mysql_database) or die('Failed to select database<br>'.mysqli_error($db));
   mysqli_set_charset($db, 'utf8');
   mysqli_query($db, 'SET NAMES "UTF8"');
   mysqli_query($db, "SET collation_connection='utf8_general_ci'");
   mysqli_query($db, "SET collation_server='utf8_general_ci'");
   mysqli_query($db, "SET character_set_client='utf8'");
   mysqli_query($db, "SET character_set_connection='utf8'");
   mysqli_query($db, "SET character_set_results='utf8'");
   mysqli_query($db, "SET character_set_server='utf8'");
   if (!empty($action))
   {
      if ($action == 'delete')
      {
         $sql = "DELETE FROM ".$mysql_table." WHERE `username` = '$id'";
         mysqli_query($db, $sql);
         mysqli_close($db);
         header('Location: '.basename(__FILE__));
         exit;
      }
      else
      if ($action == 'update')
      {
         $sql = "UPDATE `".$mysql_table."` SET `username` = '$username', ";
         if (!empty($_POST['password']))
         {
            $crypt_pass = md5($_POST['password']);
            $sql = $sql . "`password` = '$crypt_pass',";
         }
         $sql = $sql . " `fullname` = '$fullname', `email` = '$email', `role` = '$role', `active` = $active WHERE `username` = '$id'";
         mysqli_query($db, $sql);
         mysqli_close($db);
         header('Location: '.basename(__FILE__));
         exit;
      }
      else
      if ($action == 'create')
      {
         $sql = "SELECT username FROM ".$mysql_table." WHERE username = '".$_POST['username']."'";
         $result = mysqli_query($db, $sql);
         if ($data = mysqli_fetch_array($result))
         {
            echo 'User already exists!';
            exit;
         }
         $crypt_pass = md5($_POST['password']);
         $sql = "INSERT `".$mysql_table."` (`username`, `password`, `fullname`, `email`, `role`, `active`) VALUES ('$username', '$crypt_pass',  '$fullname', '$email', '$role', $active)";
         mysqli_query($db, $sql);
         mysqli_close($db);
         header('Location: '.basename(__FILE__));
         exit;
      }
      else
      if ($action == 'logout')
      {
         session_unset();
         session_destroy();
         setcookie('admin_password', '', time() - 3600);
         header('Location: '.basename(__FILE__));
         exit;
      }
   }
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>User Administrator</title>
<style type="text/css">
* 
{
   box-sizing: border-box;
}
body
{
   background-color: #FFFFFF;
   margin: 6px;
   font-size: 13px;
   font-family: Arial;
   font-weight: normal;
   font-style: normal;
   text-decoration: none;
   color: #000000;
}
th
{
   font-size: 13px;
   font-family: Arial;
   font-weight: 1;
   font-style: normal;
   text-decoration: none;
   background-color: #193441;
   color: #FFFFFF;
   text-align: left;
}
td
{
   font-size: 13px;
   font-family: Arial;
   font-weight: normal;
   font-style: normal;
   text-decoration: none;
   color: #000000;
}
input, select
{
   font-size: 13px;
   font-family: Arial;
   font-weight: normal;
   font-style: normal;
   text-decoration: none;
   color: #000000;
   border:1px #000000 solid;
}
.clickable
{
   cursor: pointer;
}
.container
{
   padding: 15px;
   text-align: left;
   width: 100%;
}
td, th 
{
   padding: 0;
}
.table 
{
   background-color: transparent;
   border: 1px solid #DDDDDD;
   border-collapse: collapse;
   border-spacing: 0;
   max-width: 100%;
   width: 100%;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td 
{
   padding: 8px;
   line-height: 1.4285;
   vertical-align: top;
   border-top: 1px solid #DDDDDD;
}
.table > thead > tr > th 
{
   vertical-align: bottom;
   border-bottom: 2px solid #DDDDDD;
}
.table > caption + thead > tr:first-child > th, .table > colgroup + thead > tr:first-child > th, .table > thead:first-child > tr:first-child > th, .table > caption + thead > tr:first-child > td, .table > colgroup + thead > tr:first-child > td, .table > thead:first-child > tr:first-child > td 
{
   border-top: 0;
}
.table-hover > tbody > tr:hover > td
{
   background-color: #F5F5F5;
}
.table-striped>tbody>tr:nth-child(odd)>td
{
   background-color: #F9F9F9;
}
th
{
   background-color: #193441;
   color: #FFFFFF;
   font-weight: normal;
}
.form-control 
{
   display: block;
   width: 100%;
   margin-bottom: 15px;
   padding: 6px 12px;
   font-family: Arial;
   font-size: 13px;
   line-height: 1.4285;
   color: #555555;
   background-color: #FFFFFF;
   border: 1px solid #CCCCCC;
   border-radius: 4px;
   background-image: none;
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075);
   transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
.form-control:focus 
{
   border-color: #193441;
   outline: 0;
   box-shadow: inset 0px 1px 1px rgba(0,0,0,0.075), 0px 0px 8px rgba(25,52,65,0.60);
}
label
{
   display: block;
   padding: 6px 0;
   text-align: left;
}
.btn
{
   display: inline-block;
   padding: 6px 12px;
   margin-bottom: 0;
   font-family: Arial;
   font-weight: normal;
   font-size: 13px;
   text-align: center;
   text-decoration: none;
   white-space: nowrap;
   vertical-align: middle;
   cursor: pointer;
   user-select: none;
   background-color: #193441;
   border: 1px solid #193441;
   border-radius: 4px;
   color: #FFFFFF;
}
#header
{
   margin-bottom: 6px;
}
#filter
{
   float: right;
}
#filter input
{
   display: inline-block;
   vertical-align: middle;
   width: 16em;
   padding: 5px 10px;
}
#filter label
{
   display: inline-block;
   max-width: 100%;
   font-size: 13px;
   font-family: Arial;
}
.filter-hide
{
   display: none !important;
}
#pagination
{
   display: inline-block;
   list-style: none;
   padding: 0;
   border-radius: 4px;
   font-family: Arial;
   font-weight: normal;
   font-size: 8px;
}
#pagination > li
{
   display: inline;
   font-size: 13px;
}
#pagination > li > a, #pagination > li > span
{
   position: relative;
   float: left;
   padding: 6px 12px 6px 12px;
   text-decoration: none;
   background-color: #FFFFFF;
   border: 1px #DDDDDD solid;
   color: #193441;
   margin-left: -1px;
}
#pagination > li:first-child > a, #pagination > li:first-child > span
{
   margin-left: 0;
   border-bottom-left-radius: 4px;
   border-top-left-radius: 4px;
}
#pagination > li:last-child > a, #pagination > li:last-child > span
{
   border-bottom-right-radius: 4px;
   border-top-right-radius: 4px;
}
#pagination > li > a:hover, #pagination > li > span:hover, #pagination > li > a:focus, #pagination > li > span:focus 
{
   background-color: #CCCCCC;
   color: #23527C;
}
#pagination > .active > a, #pagination > .active > span, #pagination > .active > a:hover, #pagination > .active > span:hover, #pagination > .active > a:focus, #pagination > .active > span:focus
{
   z-index: 2;
   background-color: #193441;
   border-color: #193441;
   color: #FFFFFF;
   cursor: default;
}
#pagination > .disabled > span, #pagination > .disabled > span:hover, #pagination > .disabled > span:focus, #pagination > .disabled > a, #pagination > .disabled > a:hover, #pagination > .disabled > a:focus 
{
   background-color: #FFFFFF;
   color: #777777;
   cursor: not-allowed;
}
.paginate-show
{
   display: table-row;
}
.paginate-hide
{
   display: none;
}
#footer
{
   margin-top: 10px;
   text-align:right;
}
.icon-edit, .icon-delete
{
   display: inline-block;
}
.icon-edit::before
{
   display: inline-block;
   width: 13px;
   height: 13px;
   content: " ";
   background: url('data:image/svg+xml,%3csvg%20style%3d%22fill:%23000000%22%20viewBox%3d%220%200%2040%2040%22%20version%3d%221.1%22%20xmlns%3d%22http://www.w3.org/2000/svg%22%3e%0d%0a%20%20%20%3cpath%20d%3d%22M12%2034%20L14%2032%20L9%2027%20L7%2029%20L7%2031%20L9%2031%20L9%2034Z%20%20M23%2014%20C23%2013%2c%2023%2013%2c%2023%2013%20C23%2013%2c%2023%2013%2c%2023%2013%20L10%2025%20C10%2025%2c%2010%2026%2c%2010%2026%20C10%2026%2c%2010%2026%2c%2011%2026%20C11%2026%2c%2011%2026%2c%2011%2026%20L23%2014%20C23%2014%2c%2023%2014%2c%2023%2014%20Z%20M22%209%20L32%2019%20L13%2037%20L4%2037%20L4%2028Z%20%20M37%2011%20C37%2012%2c%2037%2013%2c%2037%2013%20L33%2017%20L24%208%20L27%204%20C28%204%2c%2029%203%2c%2029%203%20C30%203%2c%2031%204%2c%2031%204%20L37%209%20C37%2010%2c%2037%2011%2c%2037%2011%22/%3e%0d%0a%3c/svg%3e%0d%0a') no-repeat center center;
}
.icon-delete::before
{
   display: inline-block;
   width: 13px;
   height: 13px;
   content: " ";
   background: url('data:image/svg+xml,%3csvg%20style%3d%22fill:%23000000%22%20viewBox%3d%220%200%2040%2040%22%20version%3d%221.1%22%20xmlns%3d%22http://www.w3.org/2000/svg%22%3e%0d%0a%20%20%20%3cpath%20d%3d%22M33%2027%20C33%2027%2c%2034%2028%2c%2034%2029%20C34%2029%2c%2033%2030%2c%2033%2030%20L30%2033%20C30%2033%2c%2029%2034%2c%2028%2034%20C28%2034%2c%2027%2033%2c%2027%2033%20L20%2026%20L14%2033%20C13%2033%2c%2013%2034%2c%2012%2034%20C12%2034%2c%2011%2033%2c%2011%2033%20L8%2030%20C7%2030%2c%207%2029%2c%207%2029%20C7%2028%2c%207%2027%2c%208%2027%20L14%2020%20L8%2014%20C7%2013%2c%207%2013%2c%207%2012%20C7%2012%2c%207%2011%2c%208%2011%20L11%208%20C11%207%2c%2012%207%2c%2012%207%20C13%207%2c%2013%207%2c%2014%208%20L20%2014%20L27%208%20C27%207%2c%2028%207%2c%2028%207%20C29%207%2c%2030%207%2c%2030%208%20L33%2011%20C33%2011%2c%2034%2012%2c%2034%2012%20C34%2013%2c%2033%2013%2c%2033%2014%20L26%2020Z%20%22/%3e%0d%0a%3c/svg%3e%0d%0a') no-repeat center center;
}
</style>
<script type="text/javascript" src="jquery-3.6.0.min.js"></script>
<script type="text/javascript">
$(document).ready(function()
{
   $('#filter input').on('propertychange input', function(e)
   {
      $('.no-results').remove();
      var $this = $(this);
      var search = $this.val().toLowerCase();
      var $target = $('.table');
      var $rows = $target.find('tbody tr');
      if (search == '') 
      {
         $rows.removeClass('filter-hide');
         buildNav();
         paginate();
      } 
      else 
      {
         $rows.each(function()
         {
            var $this = $(this);
            $this.text().toLowerCase().indexOf(search) === -1 ? $this.addClass('filter-hide') : $this.removeClass('filter-hide');
         })
         buildNav();
         paginate();
         if ($target.find('tbody tr:visible').size() === 0) 
         {
            var col_span = $target.find('tr').first().find('td').size();
            var no_results = $('<tr class="no-results"><td colspan="'+col_span+'"></td></tr>');
            $target.find('tbody').append(no_results);
         }
      }
   });
   $('.table').each(function()
   {
      var currentPage = 0;
      var numPerPage = 10;
      var $table = $(this);
      var numRows = $table.find('tbody tr').length;
      var numPages = Math.ceil(numRows / numPerPage);
      var $pagination = $('#pagination');
      paginate = function()
      {
         $pagination.find('li').eq(currentPage+1).addClass('active').siblings().removeClass('active');
         var $prev = $pagination.find('li:first-child');
         var $next = $pagination.find('li:last-child');
         if (currentPage == 0)
         {
            $prev.addClass('disabled');
         }
         else
         {
            $prev.removeClass('disabled');
         }
         if (currentPage == (numPages-1))
         {
            $next.addClass('disabled');
         }
         else
         {
            $next.removeClass('disabled');
         }
         $table.find('tbody tr').not('.filter-hide').removeClass('paginate-show').addClass('paginate-hide').slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).removeClass('paginate-hide').addClass('paginate-show');;
      };
      buildNav = function()
      {
         numRows = $table.find('tbody tr').not('.filter-hide').length;
         numPages = Math.ceil(numRows / numPerPage);
         $pagination.find('li').not($pagination.find('li:first-child')).not($pagination.find('li:last-child')).remove();
         for (var page = 0; page < numPages; page++)
         {
            var item = '<a>' + (page + 1) + '</a>';
            $('<li></li>').html(item)
            .bind('click', {newPage: page}, function(event)
            {
               currentPage = event.data['newPage'];
               paginate();
            }).appendTo($pagination).addClass('clickable');
         }
         $pagination.find('li').eq(1).appendTo($pagination);
      }
      buildNav();
      $pagination.find('li:nth-child(2)').addClass('active');
      $pagination.find('li:first-child').click(function()
      {
         if (currentPage > 0)
         {
            currentPage--;
         }
         paginate();
      });
      $pagination.find('li:last-child').click(function()
      {
         if (currentPage < (numPages-1))
         {
            currentPage++;
         }
         paginate();
      });
      paginate();
   });
});
</script>
</head>
<body>
<?php
   if ($admin_password != ADMIN_PASS)
   {
      echo "<div class=\"container\" style=\"text-align:center\">\n";
      echo "<h2>User Administrator</h2>\n";
      echo "<form method=\"post\" accept-charset=\"UTF-8\" action=\"" .basename(__FILE__) . "\">\n";
      echo "<input class=\"form-control\" type=\"password\" name=\"admin_password\" size=\"20\" />\n";
      echo "<input class=\"btn\" type=\"submit\" value=\"Login\" name=\"submit\" />\n";
      echo "</form>\n";
      echo "</div>\n";
   }
   else
   {
      if (!empty($action))
      {
         if (($action == 'edit') || ($action == 'new'))
         {
            $username_value = '';
            $fullname_value = '';
            $email_value = '';
            $active_value = '';
            $role_value = '';
            $sql = "SELECT * FROM ".$mysql_table." WHERE username = '".$id."'";
            $result = mysqli_query($db, $sql);
            if ($data = mysqli_fetch_array($result))
            {
               $username_value = $data['username'];
               $fullname_value = $data['fullname'];
               $email_value = $data['email'];
               $active_value = $data['active'];
               $role_value = $data['role'];
            }
            echo "<div class=\"container\">\n";
            echo "<form action=\"" . basename(__FILE__) . "\" accept-charset=\"UTF-8\" method=\"post\">\n";
            if ($action == 'new')
            {
               echo "<input name=\"action\" type=\"hidden\" value=\"create\">\n";
            }
            else
            {
               echo "<input name=\"action\" type=\"hidden\" value=\"update\">\n";
            }
            echo "<input type=\"hidden\" name=\"id\" value=\"". $id . "\">\n";
            echo "<label for=\"username\">Username</label>\n";
            echo "<input class=\"form-control\" id=\"username\" name=\"username\" size=\"50\" type=\"text\" value=\"" . $username_value . "\">\n";
            echo "<label for=\"password\">Password</label>\n";
            echo "<input class=\"form-control\" id=\"password\" name=\"password\" size=\"50\" type=\"password\" value=\"\">\n";
            echo "<label for=\"fullname\">Fullname</label>\n";
            echo "<input class=\"form-control\" id=\"fullname\" name=\"fullname\" size=\"50\" type=\"text\" value=\"" . $fullname_value . "\">\n";
            echo "<label for=\"email\">Email</label>\n";
            echo "<input class=\"form-control\" id=\"email\" name=\"email\" size=\"50\" type=\"text\" value=\"" . $email_value . "\">\n";
            echo "<label for=\"role\">Role</label>\n";
            echo "<select class=\"form-control\" id=\"role\" name=\"role\" size=\"1\">\n";
            $roles = array("Administrator","Member","Guest");
            for ($i=0; $i<count($roles); $i++)
            {
               $selected = ($roles[$i] == $role_value) ? "selected" : "";
               echo "<option value=\"$roles[$i]\" $selected>$roles[$i]</option>\n";
            }
            echo "</select>\n";
            echo "<label for=\"active\">Status</label>\n";
            echo "<select class=\"form-control\" name=\"active\" size=\"1\"><option " . ($active_value == "0" ? "selected " : "") . "value=\"0\">inactive</option><option " . ($active_value != "0" ? "selected " : "") . "value=\"1\">active</option></select>\n";
            echo "<input class=\"btn\" type=\"submit\" name=\"cmdSubmit\" value=\"Save\">";
            echo "&nbsp;&nbsp;";
            echo "<input class=\"btn\" name=\"cmdBack\" type=\"button\" value=\"Cancel\" onclick=\"location.href='" . basename(__FILE__) . "'\">\n";
            echo "</form>\n";
            echo "</div>\n";
         }
      }
      else
      {
         echo "<div id=\"header\"><a class=\"btn\" href=\"" . basename(__FILE__) . "?action=new\">New User</a>&nbsp;&nbsp;<a class=\"btn\" href=\"" . basename(__FILE__) . "?action=logout\">Logout</a>\n";
         echo "<div id=\"filter\">\n";
         echo "<label>Search: </label> <input class=\"form-control\" placeholder=\"\" type=\"search\">\n";
         echo "</div>\n</div>\n";
         echo "<table class=\"table table-striped table-hover\">\n";
         echo "<thead><tr><th>Username</th><th>Fullname</th><th>Email</th><th>Status</th><th>Action</th></tr></thead>\n";
         echo "<tbody>\n";
         $sql = "SELECT * FROM ".$mysql_table;
         $result = mysqli_query($db, $sql);
         while ($data = mysqli_fetch_array($result))
         {
            echo "<tr>\n";
            echo "<td>" . $data['username'] . "</td>\n";
            echo "<td>" . $data['fullname'] . "</td>\n";
            echo "<td>" . $data['email'] . "</td>\n";
            echo "<td>" . ($data['active'] == "0" ? "inactive" : "active") . "</td>\n";
            echo "<td>\n";
            echo "   <a href=\"" . basename(__FILE__) . "?action=edit&id=" . $data['username'] . "\" title=\"Edit\"><i class=\"icon-edit\"></i></a>&nbsp;\n";
            echo "   <a href=\"" . basename(__FILE__) . "?action=delete&id=" . $data['username'] . "\" title=\"Delete\" onclick=\"return confirm('Are you sure?')\"><i class=\"icon-delete\"></i></a>\n";
            echo "</td>\n";
            echo "</tr>\n";
         }
         echo "</tbody>\n";
         echo "</table>\n";
         echo "<div id=\"footer\">\n";
         echo "<ul id=\"pagination\">\n";
         echo "<li class=\"disabled\"><a href=\"#\">&laquo; Prev</a></li>\n";
         echo "<li class=\"disabled\"><a href=\"#\">Next &raquo;</a></li>\n";
         echo "</ul>\n";
         echo "</div>\n";
      }
   }
?>
</body>
</html>

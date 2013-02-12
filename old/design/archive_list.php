<?php

$rowsPerPage = 20;

// by default we show first page
$pageNum = 1;

// if $_GET['page'] defined, use it as page number
if(isset($_GET['page']))
{
    $pageNum = $_GET['page'];
}

// counting the offset
$offset = ($pageNum - 1) * $rowsPerPage;

global $link;
$result = mysql_query("select * from bug where isarchive = 1 LIMIT $offset,$rowsPerPage", $link);

while($row = mysql_fetch_array($result))
{
  echo "<div class='bug_main_div' style='margin-left: 40px;'>";
   echo "<a href='?module=bug&action=new&id=".$row['id'] ."'>".$row['id']."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['title'] ."</a>". '<br>';
   echo "</div>";
}


$query_cnt   = "SELECT COUNT(*) AS numrows FROM bug where isarchive = 1";
$result_cnt  = mysql_query($query_cnt,$link) or die('Error, query failed');
$row_cnt     = mysql_fetch_array($result_cnt, MYSQL_ASSOC);
$numrows = $row_cnt['numrows'];

// how many pages we have when using paging?
$maxPage = ceil($numrows/$rowsPerPage);

// print the link to access each page
$self = "?module=archive&action=list";
$nav  = '';

for($page = 1; $page <= $maxPage; $page++)
{
   if ($page == $pageNum)
   {
      $nav .= " $page "; // no need to create a link to current page
   }
   else
   {
      $nav .= " <a href=\"$self&page=$page\">$page</a> ";
   }
}

if ($pageNum > 1)
{
   $page  = $pageNum - 1;
   $prev  = " <a href=\"$self&page=$page\">[Prev]</a> ";

   $first = " <a href=\"$self&page=1\">[First Page]</a> ";
}
else
{
   $prev  = '&nbsp;'; // we're on page one, don't print previous link
   $first = '&nbsp;'; // nor the first page link
}

if ($pageNum < $maxPage)
{
   $page = $pageNum + 1;
   $next = " <a href=\"$self&page=$page\">[Next]</a> ";

   $last = " <a href=\"$self&page=$maxPage\">[Last Page]</a> ";
}
else
{
   $next = '&nbsp;'; // we're on the last page, don't print next link
   $last = '&nbsp;'; // nor the last page link
}

// print the navigation link
echo "<span style='margin-left: 40px;'>". "Page: ". $first . $prev . $nav . $next . $last . "</span>";




?>

<script type="text/javascript">
    var _bugKickKey = '<?php echo $company->api_key; ?>',
        _bugKickPID = '<?php echo $project->api_id; ?>',
        _widgetStyle = '322';
    (function(d) {
        var s = d.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = ('https:' == d.location.protocol ? 'https://' : 'http://')
            + '<?php echo Yii::app()->params['bugkickApiSettings']['scriptURL']; ?>';
        d.getElementsByTagName('head')[0].appendChild(s);
    })(document);
</script>
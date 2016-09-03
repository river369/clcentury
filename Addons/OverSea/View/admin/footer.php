<div data-role="footer" data-position="fixed"  data-theme="a">
    <div data-role="navbar" data-iconpos="left">
        <ul>
            <li><a href="../../Controller/FreelookDispatcher.php?c=index" <?php echo $isDiscover == 1 ? "class='ui-btn-active'" : '' ?> data-icon="search" rel="external">求易知</a></li>
            <li><a href="../../Controller/AuthUserDispatcher.php?c=publishService" <?php echo $isPublishService == 1 ? "class='ui-btn-active'" : '' ?> data-icon="action" rel="external">发易知</a></li>
            <li><a href="../../Controller/AuthUserDispatcher.php?c=mine" <?php echo $isMine == 1 ? "class='ui-btn-active'" : '' ?> data-icon="user" rel="external">我的</a></li>
        </ul>
    </div><!-- /navbar -->
</div>
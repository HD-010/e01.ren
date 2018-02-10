<style>
.layout{
    background-color: #2E2733;
    position: fixed;
    width: 100%;
    height: 100%;
    bottom: 0;
    left: 0;
}
.home_chart1{
	width: 80%;
    height: 100%;
    border: 0;
}

.home_chart2{
    width: 20%;
    height: 60%;
    position: fixed;
    color: yellow;
    background-color: yellow;
    right: 0;
    border: 0;
    bottom: 10%;
}

</style>
<script src="/assets/js/app/home.js"></script> 
<?php echo $data;?>

<div class="layout">
	<div class="homeChart1" id="homeChart1"></div>
</div>
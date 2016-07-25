<template>
	<div class="tooltip-container">
		<i class="fa fa-question-circle"></i>
		<div class="tooltip top" role="tooltip"> 
			<div class="tooltip-arrow"></div> 
			<div class="tooltip-inner"> {{ message }} </div>
		</div>
	</div>
</template>

<script>
	export default {
		props: ['message']
	}
</script>

<style lang="scss" scoped>
	.tooltip-container {
		display: inline-block;
		position: relative;

		&:hover > .tooltip {
			left: 50%;
			opacity: 1;
		}
	}

	.tooltip {
		z-index: 99;
		position: absolute;
		bottom: 120%;
		margin-bottom: 5px;
		margin-left: -80px;
		padding: 7px;
		min-width: 160px;
		border-radius: 3px;
		background-color: #000;
		background-color: hsla(0, 0%, 20%, 0.9);
		color: #fff;
		text-align: center;
		font-size: 14px;
		line-height: 1.2;
		left: -99999px;
		opacity: 0;
		transition: opacity .3s ease;

		.tooltip-arrow {
			position: absolute;
			bottom: -5px;
			left: 50%;
			margin-left: -5px;
			width: 0;
			border-top: 5px solid #000;
			border-top: 5px solid hsla(0, 0%, 20%, 0.9);
			border-right: 5px solid transparent;
			border-left: 5px solid transparent;
			font-size: 0;
			line-height: 0;
		}
	}

</style>
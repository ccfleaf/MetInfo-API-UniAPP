<template>
	<view>
		<scroll-view scroll-x class="bg-white nav text-center">
			<view class="cu-item" :class="index==TabCur?'text-orange cur':''" v-for="(item,index) in InfoList" :key="index" @tap="tabSelect" :data-id="index">
				{{item.title}}
			</view>
		</scroll-view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				TabCur: 0,
				scrollLeft: 0,
				InfoList:[{title:"公司简介",id:0},
						  {title:"人才招聘",id:0},
						  {title:"联系我们",id:0}]
			};
		},
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60
			}
		}
	}
</script>

<style>
</style>

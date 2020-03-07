<template>
	<view>
		<scroll-view scroll-x class="bg-white nav text-center">
			<view class="cu-item" :class="index==TabCur?'text-orange cur':''" v-for="(item,index) in InfoList" :key="index" @tap="tabSelect" :data-id="index">
				{{item.title}}
			</view>
		</scroll-view>
		
		<!--公司简介-->
		<view  :style="TabCur !=0?'display:none':''">
			<view class="article-content margin">
				<rich-text :nodes="htmlNodes"></rich-text>
			</view>
		</view>

		<!--人才招聘-->
		<view  :style="TabCur !=1?'display:none':''">
			<view v-for="(item, index) in jobs" :key="index"
				class="goods-item">
				<view class="cu-bar bg-white solid-bottom m-t">
					<view class="action">
						<text class="cuIcon-titles text-orange ">{{item.position}}</text> 
						<text class="cuIcon-location text-gray"><span class="uni-text-small">{{item.place}}</span></text> 
						<text class="cuIcon-people text-gray uni-text-small"><span class="uni-text-small">{{item.count}}</span></text> 
						<text class="cuIcon-moneybag text-gray "><span class="uni-text-small">{{item.deal}}</span></text>
					</view>
				</view>
				<view class="article-content margin">
					<rich-text :nodes="item.content"></rich-text>
				</view>
			</view>
		</view>
					
		<!--联系我们-->
		<view  :style="TabCur !=2?'display:none':''">
			<view class="cu-bar bg-white solid-bottom m-t">
				<view class="action">
					<text class="cuIcon-titles text-orange "></text> 联系方式
				</view>
			</view>
			<view class="cu-list menu" :class="[menuBorder?'sm-border':'',menuCard?'card-menu margin-top':'']">
				<view class="cu-item">
					<view class="content">
						<text class="cuIcon-we text-black"></text>
						<text class="text-grey">网站：{{Infos.web}}</text>
					</view>
				</view>
				<view class="cu-item">
					<view class="content">
						<text class="cuIcon-phone text-black"></text>
						<text class="text-grey">电话：{{Infos.phone}}</text>
					</view>
				</view>
				<view class="cu-item">
					<view class="content">
						<text class="cuIcon-mail text-black"></text>
						<text class="text-grey">Email：{{Infos.email}}</text>
					</view>
				</view>
				<view class="cu-item">
					<view class="content">
						<text class="cuIcon-people text-black"></text>
						<text class="text-grey">联系人：{{Infos.contact}}</text>
					</view>
				</view>
			</view>
			<view class="cu-bar bg-white solid-bottom m-t">
				<view class="action">
					<text class="cuIcon-titles text-orange "></text> 公司位置
				</view>
			</view>
			<view class="uni-common-mt">
				<view>
					<map :latitude="location.latitude" :longitude="location.longitude" :markers="covers">
					</map>
				</view>
			</view>
		</view>
	</view>

</template>

<script>
	var _self;
	var api = require('@/common/api.js');
	import htmlParser from '@/common/html-parser'
	export default {
		data() {
			return {
				TabCur: 0,
				scrollLeft: 0,
				htmlNodes: [],
				InfoList:[{title:"公司简介",id:0},
						  {title:"人才招聘",id:0},
						  {title:"联系我们",id:0}],
				Infos:{},
				jobs:[],
				covers: [],
				location: {latitude:40, longitude:116}
				};
		},
		onLoad(event) {
			//this.getDetail();
			this.getContact();
		},
		methods: {
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60;
				},
			getContact() {
			api.get({
				url: '?c=about&a=doabout',
				data: {
					no: 1,
				},
				success: data => {
					if(data.data.length ==0){
						return
					}
					var htmlString = data.data.profile.replace(/\\/g, "").replace(/<img/g, "<img style=\"display:none;\"");
					this.htmlNodes = htmlParser(htmlString);
					this.Infos = data.data.touch;
					this.jobs = data.data.job;
					this.covers = [{}];
					this.location.latitude = this.covers[0].latitude = data.data.touch.latitude;
					this.location.longitude = this.covers[0].longitude = data.data.touch.longitude;
					this.covers[0].iconPath = '/static/location@3x.png';

				}
			});
			}
		}
	}
</script>

<style>
</style>

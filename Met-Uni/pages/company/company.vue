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
			<view class="article-content margin">
				<rich-text :nodes="htmlNodes"></rich-text>
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
					<map :latitude="40.009645" :longitude="116.333374" :markers="covers">
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
				htmlNodes: ["附近的开始啦飞机的数量范德萨离开房间都是"],
				InfoList:[{title:"公司简介",id:0},
						  {title:"人才招聘",id:0},
						  {title:"联系我们",id:0}],
				Infos:{},
				covers: [{
					latitude: 40.009645,
					longitude: 116.333374,
					iconPath: '/static/location@3x.png'}]
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
			getDetail() {
				uni.request({
					url: 'https://unidemo.dcloud.net.cn/api/news/36kr/5292131',
					success: (data) => {

						if (data.statusCode == 200) {
							var htmlString = data.data.content.replace(/\\/g, "").replace(/<img/g, "<img style=\"display:none;\"");
							this.htmlNodes = htmlParser(htmlString);
						}
					},
					fail: () => {
						console.log('fail');
					}
				});
			},
			getContact() {
			_self = this;
			api.get({
				url: '?c=about&a=docontact',
				data: {
					no: 558,
				},
				success: data => {
					if(data.data.length ==0){
						return
					}
					_self.Infos = data.data;
				}
			});
			}
		}
	}
</script>

<style>
</style>

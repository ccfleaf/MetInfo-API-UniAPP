<template>
	<view>
		<view class="banner" @click="goDetail(banner)">
			<image class="banner-img" :src="banner.imgurl"></image>
			<view class="banner-title">{{ banner.title }}</view>
		</view>
		<view class="uni-list">
			<view class="uni-list-cell" hover-class="uni-list-cell-hover" v-for="(value, key) in listData" :key="key" @click="goDetail(value)">
				<view class="uni-media-list">
					<image class="uni-media-list-logo" :src="value.imgurl"></image>
					<view class="uni-media-list-body">
						<view class="uni-media-list-text-top">{{ value.title }}</view>
						<view class="uni-media-list-text-bottom">
							<text>{{ value.publisher }}</text>
							<text>{{ value.updatetime }}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
		<uni-load-more :status="status"  :icon-size="16" :content-text="contentText" />
	</view>
</template>

<script>
import uniLoadMore from '@/components/uni-load-more/uni-load-more.vue';
import htmlParser from '@/common/html-parser'
var dateUtils = require('../../common/util.js').dateUtils;
var api = require('@/common/api.js');

export default {
	components: {
		uniLoadMore
	},
	data() {
		return {
			banner: {},
			listData: [],
			last_id: '',
			reload: false,
			status: 'more',
			contentText: {
				contentdown: '上拉加载更多',
				contentrefresh: '加载中',
				contentnomore: '没有更多'
			}
		};
	},
	onLoad() {
		this.getBanner();
		this.getList();
	},
	onPullDownRefresh() {
		this.reload = true;
		this.last_id = '';
		this.getBanner();
		this.getList();
	},
	onReachBottom() {
		this.status = 'more';
		this.getList();
	},
	methods: {
		getBanner() {
			let data = {
				column: 'id,title,publisher,imgurl,updatetime' //需要的字段名
			};
			api.get({
				url: '?c=news&a=donewsbanner',
				data: data,
				success: data => {
					uni.stopPullDownRefresh();
					if (data.code == 1) {
						this.banner = data.data;
					}
				},
				fail: (data, code) => {
					console.log('fail' + JSON.stringify(data));
				}
			});
		},
		getList() {
			var data = {
				column: 'id,title,publisher,imgurl,updatetime' //需要的字段名
			};
			if (this.last_id) {
				//说明已有数据，目前处于上拉加载
				this.status = 'loading';
				data.minId = this.last_id;
				//data.time = new Date().getTime() + '';
				data.pageSize = 10;
			}
			api.get({
				url: '?c=news&a=donewslist',
				data: data,
				success: data => {
					if (data.data.length != 0) {
						let list = this.setTime(data.data);
						this.listData = this.reload ? list : this.listData.concat(list);
						this.last_id = list[list.length - 1].id;
						this.reload = false;
					}
					else {
						this.reload = true;
						this.status = "no more";
					}
				},
				fail: (data, code) => {
					console.log('fail' + JSON.stringify(data));
				}
			});
		},
		goDetail: function(e) {
			// 				if (!/前|刚刚/.test(e.published_at)) {
			// 					e.published_at = dateUtils.format(e.published_at);
			// 				}
			let detail = {
				publisher: e.publisher,
				imgurl: e.imgurl,
				id: e.id,
				updatetime: e.updatetime,
				title: e.title
			};
			uni.navigateTo({
				url: 'detail?detailDate=' + encodeURIComponent(JSON.stringify(detail))
			});
		},
		setTime: function(items) {
			var newItems = [];
			items.forEach(e => {
				newItems.push({
					publisher: e.publisher,
					imgurl: e.imgurl,
					id: e.id,
					updatetime: dateUtils.format(e.updatetime),
					title: e.title
				});
			});
			return newItems;
		}
	}
};
</script>

<style>
.banner {
	height: 360upx;
	overflow: hidden;
	position: relative;
	background-color: #ccc;
}

.banner-img {
	width: 100%;
}

.banner-title {
	max-height: 84upx;
	overflow: hidden;
	position: absolute;
	left: 30upx;
	bottom: 30upx;
	width: 90%;
	font-size: 32upx;
	font-weight: 400;
	line-height: 42upx;
	color: white;
	z-index: 11;
}

.uni-media-list-logo {
	width: 180upx;
	height: 140upx;
}

.uni-media-list-body {
	height: auto;
	justify-content: space-around;
}

.uni-media-list-text-top {
	height: 74upx;
	font-size: 28upx;
	overflow: hidden;
}

.uni-media-list-text-bottom {
	display: flex;
	flex-direction: row;
	justify-content: space-between;
}
</style>

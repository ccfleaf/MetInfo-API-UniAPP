<template>
	<view>
		<swiper class="screen-swiper" :class="dotStyle?'square-dot':'round-dot'" :indicator-dots="true" :circular="true"
		 :autoplay="true" interval="5000" duration="500">
			<swiper-item v-for="(item,index) in swiperList" :key="index">
				<image :src="item.imgurl" mode="aspectFill" v-if="item.type=='image'"></image>
				<video :src="item.imgurl" autoplay loop muted :show-play-btn="false" :controls="false" objectFit="cover" v-if="item.type=='video'"></video>
				<view class="text-item uni-bg-red" mode="aspectFill" v-if="item.type=='text'">{{item.title}}</view>
			</swiper-item>
		</swiper>

		<view class="cu-list menu-avatar" @click="navToDetailPage('product',0)">
			<view class="cu-item">
				<view class="tit-bar">
					<image class="tit-icon" src="../../static/product_selected.png" />
				</view>
				<view class="content">
					<view>
						<view class="text-cut">产品</view>
					</view>
					<view class="text-gray text-sm flex">
						<view class="text-cut">Quality Product</view>
					</view>
				</view>
				<view class="action">
					<text class="cuIcon-right text-gray"></text>
				</view>
			</view>
		</view>
		
		<view class="goods-list  dashed-top">
			<view 
				v-for="(item, index) in goodsList" :key="index"
				class="goods-item"
				@click="navToDetailPage('product',item)"
			>
				<view class="image-wrapper">
					<image class="goods-img"  :src="item.imgurl" mode="aspectFill"></image>
				</view>
				<text class="title">{{item.title}}</text>
			</view>
		</view>
	
		<view class="cu-list menu-avatar m-t" @click="navToDetailPage('news',null)">
			<view class="cu-item">
				<view class="tit-bar">
					<image class="tit-icon" src="../../static/news_selected.png" />
				</view>
				<view class="content">
					<view>
						<view class="text-cut">新闻</view>
					</view>
					<view class="text-gray text-sm flex">
						<view class="text-cut">News Report</view>
					</view>
				</view>
				<view class="action">
					<text class="cuIcon-right text-gray"></text>
				</view>
			</view>
		</view>
		
		<view class="uni-list">
			<view class="uni-list-cell" hover-class="uni-list-cell-hover" v-for="(value, key) in news" :key="key" @click="navToDetailPage('news',value)">
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
	</view>	
</template>

<script>
	var api = require('@/common/api.js');
	export default {
		data() {
			return {
				cardCur: 0,
				swiperList: [],
				dotStyle: false,
				towerStart: 0,
				goodsList:[],
				direction: '',
				news:[]
			};
		},
		onNavigationBarButtonTap(e) {
			if(e.index === 0)
				uni.navigateTo({
					url:"../login/login"
				});
		},
		onLoad() {
			this.getBanner();
			this.TowerSwiper('swiperList');
			this.getProductList();
			this.getNewsList();
		},
		methods: {
			async getProductList() {
				api.get({
					url: '?c=home&a=doproductlist',
					success: data => {
						if (data.data.length != 0) {
							this.goodsList = this.setTime(data.data);
						}
					},
					fail: (data, code) => {
						console.log('fail' + JSON.stringify(data));
					}
				});
			},
			getNewsList() {
				var data = {
					column: 'id,title,publisher,imgurl,updatetime' //需要的字段名
				};
				api.get({
					url: '?c=news&a=donewslist',
					success: data => {
						if (data.data.length != 0) {
							this.news = this.setTime(data.data);
						}
					},
					fail: (data, code) => {
						console.log('fail' + JSON.stringify(data));
					}
				});
			},
			getBanner() {
				api.get({
					url: '?c=home&a=dobanner',
					success: data => {
						if (data.data.length != 0) {
							this.swiperList = this.setTime(data.data);
						}
					},
					fail: (data, code) => {
						console.log('fail' + JSON.stringify(data));
					}
				});
			},
			setTime: function(items) {
				var newItems = [];
				items.forEach(e => {
					newItems.push({
						imgurl: api.HOST+'/'+e.imgurl,
						id: e.id,
						title: e.title,
						publisher: e.publisher,
						updatetime: e.updatetime,
						type: "image"
					});
				});
				return newItems;
			},
			//跳转
			navToDetailPage(type,option){
				//测试数据没有写id，用title代替
				if(option){
					uni.navigateTo({
					url: "/pages/"+type+"/detail?detailopt=" + encodeURIComponent(JSON.stringify(option)),
					})
				}
				else
				{
					uni.switchTab({
					url: "/pages/"+type+"/"+type,
					})
				}
			},
			DotStyle(e) {
				this.dotStyle = e.detail.value
			},
			// cardSwiper
			cardSwiper(e) {
				this.cardCur = e.detail.current
			},
			// towerSwiper
			// 初始化towerSwiper
			TowerSwiper(name) {
				let list = this[name];
				for (let i = 0; i < list.length; i++) {
					list[i].zIndex = parseInt(list.length / 2) + 1 - Math.abs(i - parseInt(list.length / 2))
					list[i].mLeft = i - parseInt(list.length / 2)
				}
				this.swiperList = list
			},

			// towerSwiper触摸开始
			TowerStart(e) {
				this.towerStart = e.touches[0].pageX
			},

			// towerSwiper计算方向
			TowerMove(e) {
				this.direction = e.touches[0].pageX - this.towerStart > 0 ? 'right' : 'left'
			},

			// towerSwiper计算滚动
			TowerEnd(e) {
				let direction = this.direction;
				let list = this.swiperList;
				if (direction == 'right') {
					let mLeft = list[0].mLeft;
					let zIndex = list[0].zIndex;
					for (let i = 1; i < this.swiperList.length; i++) {
						this.swiperList[i - 1].mLeft = this.swiperList[i].mLeft
						this.swiperList[i - 1].zIndex = this.swiperList[i].zIndex
					}
					this.swiperList[list.length - 1].mLeft = mLeft;
					this.swiperList[list.length - 1].zIndex = zIndex;
				} else {
					let mLeft = list[list.length - 1].mLeft;
					let zIndex = list[list.length - 1].zIndex;
					for (let i = this.swiperList.length - 1; i > 0; i--) {
						this.swiperList[i].mLeft = this.swiperList[i - 1].mLeft
						this.swiperList[i].zIndex = this.swiperList[i - 1].zIndex
					}
					this.swiperList[0].mLeft = mLeft;
					this.swiperList[0].zIndex = zIndex;
				}
				this.direction = ""
				this.swiperList = this.swiperList
			},
		}
	}
</script>

<style>
	.text-item {
		height: 100%;
		display: flex;
		justify-content: center;
		align-items: center;
	}

	.tower-swiper .tower-item {
		transform: scale(calc(0.5 + var(--index) / 10));
		margin-left: calc(var(--left) * 100upx - 150upx);
		z-index: var(--index);
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

	/* 商品列表 */
	.goods-list{
		display:flex;
		flex-wrap:wrap;
		padding: 0 30upx;
		background: #fff;
		}
	.goods-list	.goods-item{
			display:flex;
			flex-direction: column;
			width: 50%;
			padding: 10upx 20upx 10upx;
			&:nth-child(2n+1){
				margin-right: 4%;
			}
		}
	.goods-list	.image-wrapper{
			width: 100%;
			height: 320upx;
			border-radius: 3px;
			overflow: hidden;

		}
		.goods-img{
			width: 100%;
			height: 100%;
			opacity: 1;
		}
	.goods-list	.title{
			font-size: $font-lg;
			color: $font-color-dark;
			line-height: 40upx;
		}
	.goods-list	.price-box{
			display: flex;
			align-items: center;
			justify-content: space-between;
			padding-right: 10upx;
			font-size: 24upx;
			color: $font-color-light;
		}
	.goods-list	.price{
			font-size: $font-lg;
			color: #fa436a;
			line-height: 1;
			&:before{
				content: '￥';
				font-size: 26upx;
			}
		}
</style>

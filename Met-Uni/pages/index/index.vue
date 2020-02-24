<template>
	<view>
		<swiper class="screen-swiper" :class="dotStyle?'square-dot':'round-dot'" :indicator-dots="true" :circular="true"
		 :autoplay="true" interval="5000" duration="500">
			<swiper-item v-for="(item,index) in swiperList" :key="index">
				<image :src="item.url" mode="aspectFill" v-if="item.type=='image'"></image>
				<video :src="item.url" autoplay loop muted :show-play-btn="false" :controls="false" objectFit="cover" v-if="item.type=='video'"></video>
			</swiper-item>
		</swiper>

		<view class="cu-list menu-avatar">
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
		
		<view class="goods-list">
			<view 
				v-for="(item, index) in goodsList" :key="index"
				class="goods-item"
				@click="navToDetailPage(item)"
			>
				<view class="image-wrapper">
					<image class="goods-img"  :src="item.image" mode="aspectFill"></image>
				</view>
				<text class="title">{{item.title}}</text>
				<view class="price-box">
					<text class="price">价格:{{item.price}}元</text>
					<text>已售 {{item.sales}}</text>
				</view>
			</view>
		</view>
	
		<view class="cu-list menu-avatar m-t">
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
			<view class="uni-list-cell" hover-class="uni-list-cell-hover" v-for="(value, key) in news" :key="key" @click="goDetail(value)">
				<view class="uni-media-list">
					<image class="uni-media-list-logo" :src="value.image_url"></image>
					<view class="uni-media-list-body">
						<view class="uni-media-list-text-top">{{ value.desc }}</view>
						<view class="uni-media-list-text-bottom">
							<text>{{ value.source }}</text>
							<text>{{ value.datetime }}</text>
						</view>
					</view>
				</view>
			</view>
		</view>
	</view>	
</template>

<script>
	export default {
		data() {
			return {
				cardCur: 0,
				swiperList: [{
					id: 0,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big84000.jpg'
				}, {
					id: 1,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big37006.jpg',
				}, {
					id: 2,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big39000.jpg'
				}, {
					id: 3,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big10001.jpg'
				}, {
					id: 4,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big25011.jpg'
				}, {
					id: 5,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big21016.jpg'
				}, {
					id: 6,
					type: 'image',
					url: 'https://ossweb-img.qq.com/images/lol/web201310/skin/big99008.jpg'
				}],
				dotStyle: false,
				towerStart: 0,
				goodsList:[{
					image:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553187020783&di=bac9dd78b36fd984502d404d231011c0&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201609%2F26%2F20160926173213_s5adi.jpeg",
					title:"商务衬衫的范德萨范德萨发答案",
					price:20,
					sales:5
				},
				{
					image:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553187020783&di=bac9dd78b36fd984502d404d231011c0&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201609%2F26%2F20160926173213_s5adi.jpeg",
					title:"商务衬衫",
					price:20,
					sales:5
				},
				{
					image:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553187020783&di=bac9dd78b36fd984502d404d231011c0&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201609%2F26%2F20160926173213_s5adi.jpeg",
					title:"商务衬衫",
					price:20,
					sales:5
				},
				{
					image:"https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1553187020783&di=bac9dd78b36fd984502d404d231011c0&imgtype=0&src=http%3A%2F%2Fb-ssl.duitang.com%2Fuploads%2Fitem%2F201609%2F26%2F20160926173213_s5adi.jpeg",
					title:"商务衬衫",
					price:20,
					sales:5
				}],
				direction: '',
				news:[{image_url:"/static/QQ.png",
						desc:"测试测试",
						source:"BBC",
						datetime:"6月5日"},
						{image_url:"/static/QQ.png",
								desc:"测试测发大苏打撒范德萨范德萨发大水范德萨试",
								source:"BBC",
								datetime:"6月5日"},
						{image_url:"https://ossweb-img.qq.com/images/lol/web201310/skin/big39000.jpg",
								desc:"测试测试",
								source:"BBC",
								datetime:"6月5日"},
						{image_url:"/static/QQ.png",
								desc:"测试测试",
								source:"BBC",
								datetime:"6月5日"},
						{image_url:"/static/QQ.png",
								desc:"测试测试",
								source:"BBC",
								datetime:"6月5日"},
						{image_url:"/static/QQ.png",
								desc:"测试测试",
								source:"BBC",
								datetime:"6月5日"},
						{image_url:"/static/QQ.png",
								desc:"测试测试",
								source:"BBC",
								datetime:"6月5日"}]
			};
		},
		onNavigationBarButtonTap(e) {
			if(e.index === 0)
				uni.navigateTo({
					url:"../login/login"
				});
		},
		onLoad() {
			this.TowerSwiper('swiperList');
			// 初始化towerSwiper 传已有的数组名即可
		},
		methods: {
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

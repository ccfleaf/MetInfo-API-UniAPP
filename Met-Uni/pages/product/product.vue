<template>
	<view>
		<view class="carousel">
			<swiper indicator-dots circular=true duration="400">
				<swiper-item class="swiper-item" v-for="(item,index) in imgList" :key="index">
					<view class="image-wrapper">
						<image
							:src="item.src" 
							class="loaded" 
							mode="aspectFill"
						></image>
					</view>
				</swiper-item>
			</swiper>
		</view>
		
		<view class="introduce-section">
			<view class="title">{{title}}</view>
			<view class="bot-row">
				{{description}}
			</view>
		</view>
		
		<view class="c-list m-t">
			<view class="c-row" v-for="(item, index) in paras" :key="index">
				<text class="tit">{{item.name}}</text>
				<text class="con">{{item.value}}</text>
				<text class="yticon icon-you"></text>
			</view>
		</view>

		<scroll-view scroll-x class="bg-white nav m-t">
			<view class="flex text-center">
				<view class="cu-item flex-sub" :class="index==TabCur?'text-orange cur':''" v-for="(item,index) in InfoList" :key="index" @tap="tabSelect" :data-id="index">
					{{item.title}}
				</view>
			</view>
		</scroll-view>
				
		<view class="detail-desc">
			<rich-text :nodes="detail"></rich-text>
		</view>
	</view>
</template>

<script>
	import share from '@/components/share';
	var api = require('@/common/api.js');
	export default{
		components: {
			share
		},
		data() {
			return {
				TabCur: 0,
				scrollLeft: 0,
				title: '',
				description: '',
				paras: [],
				InfoList:[{title:"详情信息",id:0},
						  {title:"规格参数",id:0},
						  {title:"包装清单",id:0}],
				imgList: [],
				detail: '',
				content: '',
				specs: '',
				package: '',
			};
		},
		async onLoad(options){
			this.getdetail(options.id);
		},
		methods:{
			tabSelect(e) {
				this.TabCur = e.currentTarget.dataset.id;
				this.scrollLeft = (e.currentTarget.dataset.id - 1) * 60;
				this.detail = this.TabCur == 0?this.content:(this.TabCur == 1?this.specs:this.package);
				},
			// 获取产品详情
			getdetail(id) {
				api.get({
					url: '?c=product&a=dodetail&id='+id,
					success: data => {
						if(data.data.length ==0){
							return
						}
						this.paras = data.data.para;
						this.imgList = data.data.imgs;
						this.title = data.data.title;
						this.description = data.data.desc;
						this.detail = this.content = data.data.content;
						this.package = data.data.package;
						this.specs = data.data.specs;
					}
				});
			},
		},

	}
</script>

<style lang='scss'>
	.icon-you{
		font-size: $font-base + 2upx;
		color: #888;
	}
	.carousel {
		height: 722upx;
		position:relative;
		swiper{
			height: 100%;
		}
		.image-wrapper{
			width: 100%;
			height: 100%;
		}
		.swiper-item {
			display: flex;
			justify-content: center;
			align-content: center;
			height: 750upx;
			overflow: hidden;
			image {
				width: 100%;
				height: 100%;
			}
		}
		
	}
	
	/* 标题简介 */
	.introduce-section{
		background: #fff;
		padding: 20upx 30upx;
		
		.title{
			font-size: 32upx;
			color: $font-color-dark;
			height: 50upx;
			line-height: 50upx;
		}
		.price-box{
			display:flex;
			align-items:baseline;
			height: 64upx;
			padding: 10upx 0;
			font-size: 26upx;
			color:$uni-color-primary;
		}
		.price{
			font-size: $font-lg + 2upx;
		}
		.m-price{
			margin:0 12upx;
			color: $font-color-light;
			text-decoration: line-through;
		}
		.coupon-tip{
			align-items: center;
			padding: 4upx 10upx;
			background: $uni-color-primary;
			font-size: $font-sm;
			color: #fff;
			border-radius: 6upx;
			line-height: 1;
			transform: translateY(-4upx); 
		}
		.bot-row{
			display:flex;
			align-items:center;
			/*height: 50upx;*/
			font-size: $font-sm;
			color: $font-color-light;
			text{
				flex: 1;
			}
		}
	}
	
	.c-list{
		font-size: $font-sm + 2upx;
		color: $font-color-base;
		background: #fff;
		.c-row{
			display:flex;
			align-items:center;
			padding: 10upx 30upx;
			position:relative;
		}
		.tit{
			width: 140upx;
		}
		.con{
			flex: 1;
			color: $font-color-dark;
			.selected-text{
				margin-right: 10upx;
			}
		}
		.bz-list{
			height: 40upx;
			font-size: $font-sm+2upx;
			color: $font-color-dark;
			text{
				display: inline-block;
				margin-right: 30upx;
			}
		}
		.con-list{
			flex: 1;
			display:flex;
			flex-direction: column;
			color: $font-color-dark;
			line-height: 40upx;
		}
		.red{
			color: $uni-color-primary;
		}
	}

	/*  详情 */
	.detail-desc{
		background: #fff;
		padding: 20upx 30upx;;
		.d-header{
			display: flex;
			justify-content: center;
			align-items: center;
			height: 80upx;
			font-size: $font-base + 2upx;
			color: $font-color-dark;
			position: relative;
				
			text{
				padding: 0 20upx;
				background: #fff;
				position: relative;
				z-index: 1;
			}
			&:after{
				position: absolute;
				left: 50%;
				top: 50%;
				transform: translateX(-50%);
				width: 300upx;
				height: 0;
				content: '';
				border-bottom: 1px solid #ccc; 
			}
		}
	}
	
</style>

<template>
	<div class="container max-w-6xl px-5 mx-auto" >
		<div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-4">
			<div class="p-5 bg-white rounded shadow-sm" v-for="(value, name) in metrics" :key="name">
				<div class="text-base text-gray-400 capitalize">{{name}}</div>
				<div class="flex items-center pt-1">
					<div class="text-2xl font-bold text-gray-900 ">{{value}}</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
props: {
    metrics: {
      type: Array,
      default: [],
      required: true,
    },
    }
};
</script>
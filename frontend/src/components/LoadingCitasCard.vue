<script setup lang="ts">
</script>

<template>
  <div class="card-wrapper" v-for="i in 15" :key="i">
    <div>
      <div class="skel title-skel" />
      <div class="time-wrapper">
        <div class="skel label-skel" style="width: 42px" />
        <div class="skel label-skel" style="width: 72px" />
        <div class="skel label-skel" style="width: 30px; margin-left: 4px" />
        <div class="skel label-skel" style="width: 60px" />
      </div>
      <div class="skel label-skel" style="width: 52px; margin-bottom: 6px" />
      <div class="sub-wrapper">
        <div class="skel text-skel" style="width: 90px" />
        <div class="skel btn-skel" style="width: 80px" />
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes shimmer {
  0% { background-position: -600px 0; }
  100% { background-position: 600px 0; }
}

.skel {
  background: linear-gradient(
    90deg,
   #d4c4b8 25%,
  #e8d8cc 50%,
  #d4c4b8 75%
  );
  background-size: 600px 100%;
  animation: shimmer 1.4s infinite linear;
  border-radius: 4px;
}

.card-wrapper {
     background: #edd9c8; 
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 10px;
    box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.05);
    padding: 16px;
    border-radius: 10px;
    border: 1.5px solid #D7CCC8;
}

.title-skel  { height: 18px; width: 140px; margin-bottom: 10px; }
.time-wrapper { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.label-skel  { height: 13px; border-radius: 3px; }
.sub-wrapper { display: flex; align-items: center; gap: 10px; margin-top: 4px; }
.text-skel   { height: 13px; border-radius: 3px; }
.btn-skel    { height: 28px; border-radius: 6px; flex-shrink: 0; }
</style>
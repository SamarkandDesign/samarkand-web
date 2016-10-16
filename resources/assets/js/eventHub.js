import Vue from 'vue'

// This file serves as a centralised location for receiving 
// and emmitting events between components
// 
// Fire an event: eventHub.$emit('my-event', {data: 'hello'})
// Listen for it: created () { eventHub.$on('my-event', this.doSomething) }
export default new Vue()
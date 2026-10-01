import test from 'node:test';
import assert from 'node:assert/strict';
import {projectSavings} from '../public/assets/savings-math.mjs';
test('zero interest is contributions only',()=>{assert.equal(projectSavings(1000,100,0,10).at(-1).balance,13000);});
test('monthly compounding agrees with closed form',()=>{const rate=.05/12,n=120;const expected=1000*(1+rate)**n+100*((1+rate)**n-1)/rate;assert.ok(Math.abs(projectSavings(1000,100,5,10).at(-1).balance-expected)<.00001);});
test('empty amounts and bounds are handled',()=>{assert.equal(projectSavings(0,0,30,50).at(-1).balance,0);assert.throws(()=>projectSavings(-1,0,5,10));assert.throws(()=>projectSavings(1,1,5,1.5));assert.throws(()=>projectSavings(NaN,1,5,10));});

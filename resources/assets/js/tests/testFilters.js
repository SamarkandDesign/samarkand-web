import mocha from 'mocha'
import {expect} from 'chai'
import {capitalize, sluggify, order} from '../filters'

describe('The capitalize filter', function () {
  it('capitalizes a string of words', function () {
    expect(capitalize('some lower-case string')).to.equal('Some Lower-case String')
  })
})

describe('The sluggify filter', function () {
  it('sluggifies a string of words', function () {
    expect(sluggify('some Awesome? string')).to.equal('some-awesome-string')
  })
})

describe('The order filter', function () {
  it('orders a list according to its property', function () {
    const list = [
      {name: 'bob', age: 33},
      {name: 'tim', age: 67},
      {name: 'sarah', age: 12}
    ]

    const orderedByAge = order(list, 'age')

    expect(orderedByAge[0].name).to.equal('sarah')
    expect(orderedByAge[2].name).to.equal('tim')
  })
})
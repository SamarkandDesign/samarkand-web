export function capitalize (text) {
  return text.split(' ')
             .map(word => word[0].toUpperCase() + word.slice(1))
             .join(' ')
}

export function sluggify (text) {
  return text
      .toLowerCase()
      .replace(/[^\w ]+/g,'')
      .replace(/ +/g,'-')
}

export function unsluggify (text) {
  return text
      .replace('-',' ')
      .replace('_',' ')
}

export function order (list, key) {
  return list.sort((a, b) => a[key] > b[key])
}
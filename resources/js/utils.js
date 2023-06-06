/**
 * Converts a file size in bytes to the nearest unit of measurement (B, kB, MB, GB, ...).
 * @param {number} size - The file size in bytes
 * @param {number} precision - The number of decimal places to include in the output.
 * @returns {string} - The formatted file size with the appropriate unit of measurement.
 */
export const filesize = (size, precision = 2) => {
  const units = ['B', 'kB', 'MB', 'GB', 'TB']
  const i = Math.floor(Math.log(size) / Math.log(1024))
  const val = (size / 1024 ** i).toFixed(precision)

  if (size < 0) {
    return 'Tipo de archivo inválido.'
  } else if (size === 0) {
    return `0 ${units[0]}`
  } else if (size < 1) {
    return `${(size * 1024).toFixed(precision)} ${units[1]}`
  } else if (size >= 1024 ** units.length - 1) {
    return `${(size / 1024 ** (units.length - 1)).toFixed(precision)} ${units[units.length - 1]}`
  } else {
    return `${val} ${units[i]}`
  }
}

const dateFormatter = new Intl.DateTimeFormat('en-US', { dateStyle: 'medium' });
const dateTimeFormatter = new Intl.DateTimeFormat('en-US', { dateStyle: 'medium', timeStyle: 'short' });

export function stringAsDate (date: string): string {
  const obj = new Date(date)

  return dateFormatter.format(obj);
}

export function stringAsDateTime (date: string): string {
  const obj = new Date(date)

  return dateTimeFormatter.format(obj);
}
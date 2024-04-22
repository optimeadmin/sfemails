const formatter = new Intl.DateTimeFormat('en-US', { dateStyle: 'medium' });

export function stringAsDate (date: string): string {
  const obj = new Date(date)

  return formatter.format(obj);
}
export type EntityDates = {
  createdAt: string,
  updatedAt: string,
}

type TypeWithDates = {
  dates: EntityDates
}

export type Layout = {
  id: number,
  uuid?: string,
  label: string,
  description?: string,
} & TypeWithDates
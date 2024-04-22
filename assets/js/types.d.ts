export type EntityDates = {
  createdAt: string,
  updatedAt: string,
}

type TypeWithDates = {
  dates: EntityDates
}

export type Layout = {
  label: string,
  description?: string,
  content?: Record<string, string | null>,
} & TypeWithDates

export type ExistentLayout = {
  id: number,
  uuid: string,
  label: string,
  description?: string,
} & Layout
export type EntityDates = {
  createdAt: string,
  updatedAt: string,
}

type TypeWithDates = {
  dates: EntityDates
}

export type Layout = {
  description?: string,
  content?: Record<string, string | null>,
}

export type ExistentLayout = {
  id: number,
  uuid: string,
  label: string,
  description?: string,
} & Layout & TypeWithDates
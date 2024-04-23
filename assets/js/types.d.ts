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
} & Layout & TypeWithDates

export type Config = {
  code: string,
  description: string,
  layoutUuid: string,
  editable: boolean,
  target: string,
} & TypeWithDates

export type ExistentConfig = {
  uuid: string,
} & Config
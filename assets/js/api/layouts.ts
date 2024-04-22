import { ExistentLayout, Layout } from '../types'
import { axiosApi } from './axiosInstances'

export async function getLayouts (signal: AbortSignal): Promise<ExistentLayout[]> {
  const { data } = await axiosApi.get(`/layouts`, { signal })

  return data as ExistentLayout[]
}

export async function saveLayout (layout: Layout | ExistentLayout): Promise<void> {
  await axiosApi.post(`/layouts`, layout)
}
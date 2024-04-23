import { ExistentLayout, Layout } from '../types'
import { axiosApi } from './axiosInstances'

export async function getLayouts (signal: AbortSignal): Promise<ExistentLayout[]> {
  const { data } = await axiosApi.get(`/layouts`, { signal })

  return data as ExistentLayout[]
}

export async function getLayout (uuid: string, signal: AbortSignal): Promise<ExistentLayout> {
  const { data } = await axiosApi.get(`/layouts/${uuid}`, { signal })

  return data as ExistentLayout
}

export async function saveLayout (layout: Layout | ExistentLayout): Promise<void> {
  if ('uuid' in layout && layout.uuid) {
    await axiosApi.patch(`/layouts/${layout.uuid}`, layout)
  } else {
    await axiosApi.post(`/layouts`, layout)
  }
}
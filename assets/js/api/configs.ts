import { ExistentConfig, ExistentLayout, Layout } from '../types'
import { axiosApi } from './axiosInstances'

export async function getConfigs (signal: AbortSignal): Promise<ExistentConfig[]> {
  const { data } = await axiosApi.get(`/configs`, { signal })

  return data as ExistentConfig[]
}

export async function getLayout (uuid: string, signal: AbortSignal): Promise<ExistentLayout> {
  const { data } = await axiosApi.get(`/layouts/${uuid}`, { signal })

  return data as ExistentLayout
}

export async function saveLayout (layout: Layout | ExistentLayout): Promise<void> {
  if ('id' in layout && layout.id) {
    await axiosApi.patch(`/layouts/${layout.uuid}`, layout)
  } else {
    await axiosApi.post(`/layouts`, layout)
  }
}
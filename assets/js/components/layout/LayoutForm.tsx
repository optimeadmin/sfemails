import React from 'react'
import { FormErrors, FormLabel, FormRow } from '../ui/form/field'
import { Col, FormControl, Row } from 'react-bootstrap'
import { FieldWithLocale, TranslatableFields } from '../ui/form/TranslatableFields'
import { ActionsContainer } from '../ui/form/ActionsContainer'
import { Link, useNavigate } from 'react-router-dom'
import { FormProvider, useForm } from 'react-hook-form'
import { Layout } from '../../types'
import { useSaveLayout } from '../../hooks/layout'
import { ButtonWithLoading } from '../ui/ButtonWithLoading'
import { ControlledCodeMirror } from '../ui/CodeMirror'

export function LayoutForm () {
  const navigate = useNavigate()
  const form = useForm<Layout>()
  const { register } = form

  const { save, isPending } = useSaveLayout(form.setError)

  async function sendForm (data: Layout) {
    console.log({ data })
    await save(data)
    navigate('/layouts')
  }

  return (
    <form onSubmit={form.handleSubmit(sendForm)}>
      <FormProvider {...form}>
        <FormRow className="mb-3" name="description">
          <FormLabel>Description</FormLabel>
          <FormControl as="textarea" {...register('description', { required: true })}/>
          <FormErrors/>
        </FormRow>

        <FormRow>
          <FormLabel>Content</FormLabel>
          <Row>
            <TranslatableFields
              render={({ locale }) => (
                <Col xs={12} xl={6}>
                  <FormRow name={`content.${locale}`}>
                    <FieldWithLocale locale={locale} className="mb-3">
                      <ControlledCodeMirror name={`content.${locale}`} rules={{ required: true }}/>
                    </FieldWithLocale>
                    <FormErrors/>
                  </FormRow>
                </Col>
              )}
            />
          </Row>
        </FormRow>
      </FormProvider>

      <ActionsContainer>
        <Link to="/layouts" className="btn btn-outline-secondary">Cancel</Link>
        <ButtonWithLoading type="submit" isLoading={isPending}>Create</ButtonWithLoading>
      </ActionsContainer>
    </form>
  )
}
